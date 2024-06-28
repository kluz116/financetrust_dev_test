namespace RecipeAPI.Models
{
    public class Recipe
    {
        public string postcode { get; set; }
        public string recipe { get; set; }
        public string delivery { get; set; }


    }

    public class RecipeCount
    {
        public string recipe { get; set; }
        public int count { get; set; }
    }

    public class BusiestPostCode
    {
        public string postcode { get; set; }
        public int deliverycount { get; set; }
    }
}
