<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecipeApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testExampleEndpoint(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testCountOfUniqueRecipes()
    {
        $response = $this->get('/api/unique-recipe-count');
        $response->assertStatus(200);
        $response->assertJsonStructure(['unique_recipes_count']);
    }

    public function testNumberOfRecipeCounts()
    {
        $response = $this->get('/api/count-per-recipe');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'count_per_recipe' => [
                '*' => [
                    'recipe',
                    'count'
                ]
            ]
        ]);
    }

    public function testFindingBusiestPostcode()
    {
        $response = $this->get('/api/busiest-postcode');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'busiest_postcode' => [
                'postcode',
                'delivery_count'
            ]
        ]);
    }

    public function testSearchingForRecipeNames()
    {
        $response = $this->get('/api/match-by-name?keywords[]=Potato&keywords[]=Veggie&keywords[]=Mushroom');
        $response->assertStatus(200);
        
        // Check if the response is an array
        $response->assertJsonIsArray();
        
        // Check if specific expected recipe names are in the response
        $response->assertJsonFragment(["Mediterranean Baked Veggies"]);
        //$response->assertJsonFragment(["Speedy Steak Fajitas"]);//fail assertion
    }

    public function testAggregatedData()
    {
        $response = $this->get('/api/aggregated-data?keywords[]=Potato&keywords[]=Veggie&keywords[]=Mushroom');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'unique_recipe_count',
                'count_per_recipe' => [
                    '*' => [
                        'recipe',
                        'count'
                    ]
                ],
                'busiest_postcode' => [
                    'postcode',
                    'delivery_count'
                ],
                'match_by_name'
            ],
            'json_result_file_path'
        ]);
    }
}
