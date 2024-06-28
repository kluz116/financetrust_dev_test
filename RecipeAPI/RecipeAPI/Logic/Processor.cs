using RecipeAPI.Models;
using System.Globalization;
using System.Text.Json;

namespace RecipeAPI.Logic
{
    public class Processor
    {
        private List<Recipe> recipes;
        private readonly IConfiguration _configuration;
        public Processor()
        {
            // Read the recipe records from the JSON file located at root of the project
            try
            {
                var recipeData = File.ReadAllText("./data.json");
                recipes = JsonSerializer.Deserialize<List<Recipe>>(recipeData);
            }
            catch (Exception ex)
            {
                ErrorHandler("Constructor:Processor()", "Entry to the processor class", ex.Message);
            }
            

        }

        public int GetUniqueRecipeCount()
        {
            int unq_count = 0;
            try
            {
                List<string> uniqueRecipeNames = recipes.Select(r => r.recipe).Distinct().ToList();
                List<Recipe> uniqueRecipes = recipes.GroupBy(r => r.recipe).Select(g => g.First()).ToList();
                unq_count = recipes.Select(r => r.recipe).Distinct().Count();
            }
            catch (Exception ex)
            {
                ErrorHandler("GetUniqueRecipeCount", "Unique Recipe Error", ex.Message);
            }
            return unq_count;
        }

        public List<RecipeCount> GetRecipeOccurrences()
        {
            List<RecipeCount> recipeCounts = new List<RecipeCount>();
            try
            {
                recipeCounts = recipes.GroupBy(r => r.recipe)
                      .OrderBy(g => g.Key)
                      .Select(g => new RecipeCount { recipe = g.Key, count = g.Count() })
                      .ToList();
            }
            catch (Exception ex)
            {
                ErrorHandler("GetRecipeOccurrences", "Recipe Occureencess Error", ex.Message);
            }
       
            return recipeCounts;
        }

        public BusiestPostCode GetPostcodeWithMostDeliveries()
        {
            BusiestPostCode postCode = new BusiestPostCode();
            try 
            {

                postCode = recipes.GroupBy(r => r.postcode).OrderByDescending(g => g.Count()).
                                            Select(g => new BusiestPostCode { postcode = g.Key, deliverycount = g.Count() })
                                         .FirstOrDefault();
            }
            catch (Exception ex)
            {
                ErrorHandler("GetPostcodeWithMostDeliveries", "PostCodes with most deliveries", ex.Message);
            }
            return postCode;
        }

        public List<string> GetRecipescontatiningCheckListAlphabetically()
        {
            List<string> sortedRecipes = new List<string>(); 
            List<string> checkList = new List<string> { "Potato", "Veggie", "Mushroom" };
            try 
            {
                sortedRecipes=recipes.Select(r => r.recipe)
                .Where(recipe => checkList.Any(keyword => recipe.IndexOf(keyword, StringComparison.OrdinalIgnoreCase) >= 0))
                .Distinct()
                .OrderBy(r => r)
                .ToList();
            }
            catch (Exception ex)
            {
                ErrorHandler("GetRecipescontatiningCheckListAlphabetically", "GetRecipescontatiningCheckListAlphabetically", ex.Message);
            }
            return sortedRecipes; 
        }

        public void ErrorHandler(string method, string errorType, string errorMessage)
        {
            try
            {
                string logPath = Path.Combine( _configuration["FilePath"],"Errors",  DateTime.Today.ToString(" yyyy_MM_dd") + ".txt");

                // Check for the file's existence, or create a new file
                if (!File.Exists(logPath))
                {
                    File.Create(logPath).Close();
                }

                using (StreamWriter w = File.AppendText(logPath))
                {
                    // using the StreamWriter class write log message in a file
                    w.WriteLine("\r\n ERROR : ");
                    w.WriteLine("{0}", DateTime.Now.ToString(CultureInfo.InvariantCulture));
                    string err = $"METHOD: {method} ERROR TYPE: {errorType} ERROR MESSAGE: {errorMessage}";
                    w.WriteLine(err);
                    w.WriteLine("____________________________________________________________________");
                    w.Flush();
                    w.Close();
                }
            }
            catch (Exception ex)
            {
                throw ex;
            }
        }
    }
}
