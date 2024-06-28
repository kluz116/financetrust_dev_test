using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using RecipeAPI.Logic;
using RecipeAPI.Models;

namespace RecipeAPI.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class RecipeController : ControllerBase
    {
        private readonly Processor prc;

        JsonResult result = null;
        public RecipeController()
        {
            prc = new Processor();
        }

        //Process Recipe Request
       
        [HttpGet("UniqueRecipeCount")]
        public IActionResult GetUniqueRecipeCount()
        {
            var count = 0; 
            try
            {
                 count = prc.GetUniqueRecipeCount();

            }
            catch (Exception ex)
            {
                prc.ErrorHandler("GetUniqueRecipeCount", "Controller Error", ex.Message);
            }

            return Ok(count);
        }


        [HttpGet("RecipeOccurrences")]
        public IActionResult GetRecipeOccurrences()
        {
            
            try
            {
                var recipecounts = prc.GetRecipeOccurrences();
                var result = new { count_per_recipe = recipecounts };
            }
            catch (Exception ex)
            {
                prc.ErrorHandler("GetRecipeOccurrences", "Controller Error", ex.Message);
            }
            return Ok(result);
            // return result = new JsonResult(recipecounts);
        }

        [HttpGet("PostcodeWithMostDeliveries")]
        public IActionResult GetPostcodeWithMostDeliveries()
        {
            try 
            {
                var busiestPostCode = prc.GetPostcodeWithMostDeliveries();
                var result = new { busiest_postcode = busiestPostCode };
            }
            catch(Exception ex) 
            {
                prc.ErrorHandler("GetPostcodeWithMostDeliveries", "Controller Error", ex.Message);
            }
            
            return Ok(result);
        }

        [HttpGet("RecipesAlphabetically")]
        public IActionResult GetRecipesWithCheckListAlphabetically()
        {
            try
            {
                var recipes = prc.GetRecipescontatiningCheckListAlphabetically();
                var result = new { match_by_name = recipes };
            }
            catch (Exception ex)
            {
                prc.ErrorHandler("GetRecipesWithCheckListAlphabetically", "Controller Error", ex.Message);
            }
           
            return Ok(result);
        }
    }
}
