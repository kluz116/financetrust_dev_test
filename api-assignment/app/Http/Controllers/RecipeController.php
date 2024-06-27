<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $json = file_get_contents(storage_path('data.json'));
        $this->data = json_decode($json, true);
    }

     /**
     * @OA\Get(
     *     path="/api/unique-recipe-count",
     *     operationId="uniqueRecipeCount",
     *     tags={"Recipes Statistics API"},
     *     summary="Get the count of unique recipes",
     *     description="Returns the Count of unique recipe names.",
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource not found"),
     * )
     */
    public function uniqueRecipeCount()
    {
        $recipes = array_column($this->data, 'recipe');
        $uniqueRecipes = array_unique($recipes);
        return response()->json(['unique_recipes_count' => count($uniqueRecipes)]);
    }

     /**
     * @OA\Get(
     *     path="/api/count-per-recipe",
     *     operationId="countPerRecipe",
     *     tags={"Recipes Statistics API"},
     *     summary="Get the count per recipe",
     *     description="Returns Count number of occurences for each unique recipe name (alphabetically ordered by recipe name).",
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource not found"),
     * )
     */
    public function countPerRecipe()
    {
        $recipes = array_column($this->data, 'recipe');
        $recipeCounts = array_count_values($recipes);
        ksort($recipeCounts);
        return response()->json($recipeCounts);
    }

    /**
     * @OA\Get(
     *     path="/api/busiest-postcode",
     *     operationId="busiestPostcode",
     *     tags={"Recipes Statistics API"},
     *     summary="Get the postcode with most delivered recipes",
     *     description="Find the postcode with most delivered recipes.",
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource not found"),
     * )
     */
    public function busiestPostcode()
    {
        $postcodes = array_column($this->data, 'postcode');
        $postcodeCounts = array_count_values($postcodes);
        arsort($postcodeCounts);
        $busiestPostcode = array_key_first($postcodeCounts);
        return response()->json(['busiest_postcode' => $busiestPostcode]);
    }

     /**
     * @OA\Get(
     *     path="/api/match-by-name",
     *     operationId="matchByName",
     *     tags={"Recipes Statistics API"},
     *     summary="Get recipes matching specific keywords",
     *     @OA\Parameter(
     *         name="keywords",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="string")),
     *         description="List of keywords to match"
     *     ),
     *     description="List the recipe names (alphabetically ordered) that contain in their name one of the following words: [Potato, Veggie, Mushroom]",
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource not found"),
     * )
     */
    
    public function matchByName(Request $request)
    {
        $keywords = $request->input('keywords', ['Potato', 'Veggie', 'Mushroom']);
    
        // Ensure $keywords is an array
        if (!is_array($keywords)) {
            $keywords = explode(',', $keywords);
        }
    
        $recipes = array_column($this->data, 'recipe');
        $filteredRecipes = array_filter($recipes, function ($recipe) use ($keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($recipe, $keyword) !== false) {
                    return true;
                }
            }
            return false;
        });
        $uniqueFilteredRecipes = array_unique($filteredRecipes);
        sort($uniqueFilteredRecipes);
        return response()->json(array_values($uniqueFilteredRecipes));
    }
}
