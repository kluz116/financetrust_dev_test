const { match } = require('assert');
const express = require('express');
const fs = require('fs');
const app = express();

/*
 * Author: Hedwig Orieba
 * Date : 27/06/2024
 * Desc: Finance Trust Bank Software Dev't Interview 
*/


/* Datasource path ... ....*/
const __Path ='./data.json'; // path to the central dataset
const _unique_recipes = './appData/unique_recipe.json'; // dumping location for the computed unique recipe names
const _occurances = './appData/recipe_occurances.json';  // dumping location for the total occurances
const _postcode_deliveries = './appData/highest_delivery.json'; // dumping location for the highest deliveries
const _matched_recipe ='./appData/matched_recipe.json'; // dumping location for matched recipe names.


/* get unique receipt names.... helper function #01 */
function getUniqueEntityAttributes(__filePath, _entityAttribute){
    return new Promise((resolve,reject)=>{
        fs.readFile(__filePath,'utf8', (err, data) =>{
            if(err) return reject(err);
            let jsonObjsArr = JSON.parse(data);
            let namesArr = [];
            let uniqueNameSet = new Set();

            if(_entityAttribute == 'recipe'){
                jsonObjsArr.forEach(elem => namesArr.push( elem.recipe ));
            }
            
            if(_entityAttribute == 'postcode'){
                jsonObjsArr.forEach(elem => namesArr.push( elem.postcode ));
            }
            
            namesArr.forEach(attr => uniqueNameSet.add( attr ));
            return resolve({elementNameSet:[...uniqueNameSet], resourceData:jsonObjsArr});
        });
    });    
}

/* returns an object definition of a provided object attribute[recipe or postcode] & it's nos of occurances....helper function #02 */
 async function getAttributeOccurrances(__filePath,_entityAttribute){
        const genericResponse = await getUniqueEntityAttributes(__filePath, _entityAttribute);
        const objsArr = genericResponse.resourceData;

        let uniqueNameObjArr = genericResponse.elementNameSet;
        let rawMapped = [];
        uniqueNameObjArr = uniqueNameObjArr.sort();

        uniqueNameObjArr.forEach(nameAttr => {
            // internal counter variable...
            let counter = 0;

            objsArr.forEach( recipeObj =>{

                /* method was called with 'recipe' actual parameter value. Count the recipes */
                if(_entityAttribute === 'recipe'){
                    if(nameAttr == recipeObj.recipe){
                        counter++;
                    }
                }

                /* method was called with 'postcode' actual parameter value. Count the postcodes. */
                if(_entityAttribute == 'postcode'){
                    if(nameAttr == recipeObj.postcode){
                        counter++;
                    }
                }
            });

            /* Conditionally initialize the respective entity array */
            if(_entityAttribute === 'recipe'){
                rawMapped.push({recipe:`${nameAttr}`, occurance:`${counter}`});
            }

            /* Conditionally initialize the respective entity array */
            if(_entityAttribute == 'postcode'){
                rawMapped.push({postcode:`${nameAttr}`, occurance:`${counter}`});
            }
        });

        return {rawMappedObjVer:rawMapped};
}


/* Endpoint that counts the number of unique recipe names. */
app.get('/api/count/uniqueRecipes/names', async (req,res)=>{
    try{
        const genericResponse = await getUniqueEntityAttributes(__Path,'recipe');
        const result = {unique_recipe_count:`${genericResponse.elementNameSet.length}`};
        fs.writeFile(`${_unique_recipes}`, JSON.stringify(result),'utf-8', err => {
            if(err) {
                throw err;
            }else{
                console.log('file "unique_recipe.json" generated successfully in the "appData" directory.');
            }
        });
        res.status(200).send(result);
    }catch(ex){
        console.log(ex);
        res.status(500).send('Error: An internal server error occured while processing resource request!');
    }
});


/*  Endpoint that counts the occurance of each unique recipe name */
app.get('/api/count/uniqueRecipes/occurances', async(req,res) => {
    try{
        const processedOccurances = await getAttributeOccurrances( __Path, 'recipe');
        const result = ({count_per_recipe:`${JSON.stringify(processedOccurances.rawMappedObjVer)}`});
        fs.writeFile(`${_occurances}`, JSON.stringify(result),'utf-8', err => {
                if(err) {
                    throw err;
                }else{
                    console.log('file "recipe_occurances.json" generated successfully in the "appData" directory.');
                }
          }
        );
        res.status(200).send(result);
    }catch(ex){
        console.log(ex);
        res.status(500).send('Error: An internal server error occured while processing resource request!');
    }
});


/* ****************************************************************************
   REMARK:
   Endpoint that returns the postcode. If there is more than one value 
   that meets a high delivery score, this endpoint returns these postcodes 
   as an array. 
******************************************************************************/
app.get('/api/postcode/deliveries/highest', async(req,res)=>{
    try{
            const processedOccurances = await getAttributeOccurrances(__Path, 'postcode');

            let occurSet = new Set();
            let occurList = [];
        
            // get the unique occurances of postcodes
            processedOccurances.rawMappedObjVer.forEach( postcodeObj => {
                occurSet.add(postcodeObj.occurance);
            });
        
            // convert the set to an array
            occurList = [...occurSet];
            occurList = occurList.sort((a,b) => a-b);
         
            let searchKeyNameArr=[]
            
            // get all postcodes that match the highest occurance value & place them inside a searchkey array
            processedOccurances.rawMappedObjVer.forEach( postcodeObj => {
                if( postcodeObj.occurance === occurList[occurList.length-1]){
                    // pick the postcode & place it into a search array.
                    searchKeyNameArr.push({postcode:`${postcodeObj.postcode}`,delivery_count:`${postcodeObj.occurance}`});
                }
            });
    
            // if more than one postcode meets the highest occurance level, return an array otherwise return the single postcode
            if(searchKeyNameArr.length > 1){
                fs.writeFile(`${_postcode_deliveries}`, JSON.stringify(searchKeyNameArr),'utf-8',err => {
                    if(err) {
                        throw err;
                    }else{
                        console.log('file "highest_delivery.json" generated successfully in the "appData" directory.');
                    }
              });
                res.status(200).send(searchKeyNameArr)
            }else{
                fs.writeFile(`${_postcode_deliveries}`, JSON.stringify(searchKeyNameArr[0]),'utf-8',err => {
                    if(err) {
                        throw err;
                    }else{
                        console.log('file "highest_delivery.json" generated successfully in the "appData" directory.');
                    }
              });
                res.status(200).send(searchKeyNameArr[0]);
            }
        }catch(ex){
            console.log(ex);
            res.status(500).send('Error: An internal server error occured while processing resource request!');
    }
});



/************* ********************************************************************************************
    List the recipe names (alphabetically ordered) that contain in their name one of the following words
    Potato,Veggie, Mushroom
**********************************************************************************************************/
app.get('/api/recipes/patterns/match', async(req,res)=>{
    try{    
            // get all the unique recipe names
            const genericResponse = await getUniqueEntityAttributes(__Path, 'recipe');
            const matchStrGroup = ['Potato','Veggie','Mushroom'];

            let uniqueNameObjArr = genericResponse.elementNameSet;
            let matchedReceipeNames = [];

            // iterate through all the unique names.
            uniqueNameObjArr.forEach(recipeName =>{
                // for each name, check if any of the strings 'Potato, Veggie, Mushroom' exists & push it into an array.
                matchStrGroup.forEach(nameSbStr => {
                    if(recipeName.includes(nameSbStr)){
                        matchedReceipeNames.push(recipeName)
                    }
                });
            });

            // Sort the matched string array...
            matchedReceipeNames = matchedReceipeNames.sort();

            fs.writeFile(`${_matched_recipe}`, JSON.stringify({ match_by_name: matchedReceipeNames }),'utf-8',err => {
                if(err) {
                    throw err;
                }else{
                    console.log('file "matched_recipe.json" generated successfully in the "appData" directory.');
                }
          });
        
            // return the result to the client...
            res.status(200).send({ match_by_name: matchedReceipeNames });
    }catch(ex){
            console.log(ex);
            res.status(500).send('Error: An internal server error occured while processing resource request!');
    }
});

const port = process.env.port || 3044;
app.listen(port, ()=> console.log(`Data processor App-listening at port ${port}...`));