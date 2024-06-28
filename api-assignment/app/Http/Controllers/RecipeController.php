<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
//@kidepo
//php artisan l5-swagger:generate

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Recipe Fixture Statistics API Documentation",
 *      description="This API provides functionality for managing and querying recipe data json file. It allows to retrieve information about various recipes, search for recipes by specific keywords, and analyze recipe data such as the number of unique recipes, recipe counts, and the most frequently delivered recipes. The API is built using Laravel and is documented using Swagger/OpenAPI. This documentation provides detailed information about each endpoint, including parameters, responses, and example requests.",
 *      termsOfService="https://www.financetrust.co.ug/terms/",
 *      @OA\Contact(
 *          email="kiyingidenispaul@proton.me"
 *      ),
 * )
 */
class RecipeController extends Controller
{
    private $data;

    public function __construct()
    {
        // Default to using a common fixtures file, can be overridden in methods belw
        $json = file_get_contents(storage_path('data.json'));
        $this->data = json_decode($json, true);
    }

    private function loadData($filePath)
    {
        $this->data = json_decode(File::get($filePath), true);
    }

    public function useCustomFixtureFile(Request $request)
    {   try {
            if ($request->has('fixtures_file') && isset($request->fixtures_file)) {
                $filePath = $request->input('fixtures_file');
                
                // Check if the file exists and is a .json file
                if (File::exists($filePath) && File::extension($filePath) === 'json') {
                    $this->loadData($filePath);
                    return true;
                } else {
                    return false;//fixture provided but failed to resolve a valid json file path.
                }
            }else{
                return true;//use default if fixtures_file not attached
            }
        } catch (\Exception $e) {
            return false;
        }
    }

     /**
     * @OA\Get(
     *     path="/api/unique-recipe-count",
     *     operationId="uniqueRecipeCount",
     *     tags={"Recipes Statistics API"},
     *     summary="Get the count of unique recipes",
     *     description="Returns the Count of unique recipe names.",
     *     @OA\Parameter(
     *         name="fixtures_file",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Path to custom .json fixtures file (optional)"
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource not found"),
     * )
     */
    public function uniqueRecipeCount(Request $request)
    {
       
        if(!$this->useCustomFixtureFile($request)){//check if custom fixture file is available else use default data file
           return response()->json(['error' => 'Failure to resolve file path ['.$request->fixtures_file.']. Please provide a valid .json fixtures file else dont pass fixtures_file to use default.'], 400);
        }
        // Extract the 'recipe' column from the data array into a new array called $recipes.
        $recipes = array_column($this->data, 'recipe');
        $uniqueRecipes = array_unique($recipes);//remove duplicates
        return response()->json(['unique_recipes_count' => count($uniqueRecipes)]);//return the count of unique recipes
    }

     /**
     * @OA\Get(
     *     path="/api/count-per-recipe",
     *     operationId="countPerRecipe",
     *     tags={"Recipes Statistics API"},
     *     summary="Get the count per recipe",
     *     description="Returns Count number of occurences for each unique recipe name (alphabetically ordered by recipe name).",
     *     @OA\Parameter(
     *         name="fixtures_file",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Path to custom .json fixtures file (optional)"
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource not found"),
     * )
     */
    public function countPerRecipe(Request $request)
    {
        if(!$this->useCustomFixtureFile($request)){
            return response()->json(['error' => 'Failure to resolve file path ['.$request->fixtures_file.']. Please provide a valid .json fixtures file else dont pass fixtures_file to use default.'], 400);
        }

         
        $recipes = array_column($this->data, 'recipe');
        // Count the occurrences of each recipe in the $recipes array.
        $recipeCounts = array_count_values($recipes);
        ksort($recipeCounts);//Sort the recipe counts by their keys
        return response()->json($recipeCounts);//Return the array as a json array
    }

    /**
     * @OA\Get(
     *     path="/api/busiest-postcode",
     *     operationId="busiestPostcode",
     *     tags={"Recipes Statistics API"},
     *     summary="Get the postcode with most delivered recipes",
     *     description="Find the postcode with most delivered recipes.",
     *     @OA\Parameter(
     *         name="fixtures_file",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Path to custom .json fixtures file (optional)"
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource not found"),
     * )
     */
    public function busiestPostcode(Request $request)
    {
        if(!$this->useCustomFixtureFile($request)){
            return response()->json(['error' => 'Failure to resolve file path ['.$request->fixtures_file.']. Please provide a valid .json fixtures file else dont pass fixtures_file to use default.'], 400);
        } 

        $postcodes = array_column($this->data, 'postcode');
        // Count the occurrences of each postcode
        $postcodeCounts = array_count_values($postcodes);
        arsort($postcodeCounts);// Sorting the counts in descending order
        $busiestPostcode = array_key_first($postcodeCounts);//returning the first up, is the biggest
        return response()->json(['busiest_postcode' => $busiestPostcode]);
    }

/**
 * @OA\Get(
 *     path="/api/match-by-name",
 *     operationId="matchByName",
 *     tags={"Recipes Statistics API"},
 *     summary="Get recipes matching specific keywords",
 *     description="List the recipe names (alphabetically ordered) that contain in their name one of the following words: [Potato, Veggie, Mushroom]",
 *     @OA\Parameter(
 *         name="keywords",
 *         in="query",
 *         required=false,
 *         @OA\Schema(
 *             type="array",
 *             @OA\Items(type="string")
 *         ),
 *         description="List of keywords to match"
 *     ),
 *     @OA\Parameter(
 *         name="fixtures_file",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string"),
 *         description="Path to custom .json fixtures file (optional)"
 *     ),
 *     @OA\Response(response=200, description="Successful operation"),
 *     @OA\Response(response=400, description="Bad request"),
 *     @OA\Response(response=404, description="Resource not found")
 * )
 */
    public function matchByName(Request $request)
    {
        if(!$this->useCustomFixtureFile($request)){
            return response()->json(['error' => 'Failure to resolve file path ['.$request->fixtures_file.']. Please provide a valid .json fixtures file else dont pass fixtures_file to use default.'], 400);
        }
 
        // Get keywords frm request or use default ones if none are provided...
        $keywords = $request->input('keywords', ['Potato', 'Veggie', 'Mushroom']);
    
        // Ensure $keywords is an array else if string is passed, form an array of them
        if (!is_array($keywords)) {
            $keywords = explode(',', $keywords);
        }
    
        $recipes = array_column($this->data, 'recipe');
        // Filter recipes that contain any of the keywords provided as storing in $filteredRecipes
        $filteredRecipes = array_filter($recipes, function ($recipe) use ($keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($recipe, $keyword) !== false) {
                    return true;
                }
            }
            return false;
        });
         // Remove duplicate andf sort
        $uniqueFilteredRecipes = array_unique($filteredRecipes);
        sort($uniqueFilteredRecipes);
        return response()->json(array_values($uniqueFilteredRecipes));
    }
}
