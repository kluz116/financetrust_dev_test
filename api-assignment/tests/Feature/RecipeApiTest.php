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
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    
    public function testUniqueRecipes()
    {
        $response = $this->get('/api/unique-recipe-count');
        $response->assertStatus(200);
        $response->assertJsonStructure(['unique_recipes_count']);
    }

    public function testRecipeCounts()
    {
        $response = $this->get('/api/count-per-recipe');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => []
        ]);
    }

    public function testBusiestPostcode()
    {
        $response = $this->get('/api/busiest-postcode');
        $response->assertStatus(200);
        $response->assertJsonStructure(['busiest_postcode']);
    }

    public function testRecipeNames()
    {
        
        $response = $this->get('/api/match-by-name?keywords[]=Potato&keywords[]=Veggie&keywords[]=Mushroom');
        $response->assertStatus(200);
        
        // Check if the response is an array
        $response->assertJsonIsArray();
        
        // Check if specific expected recipe names are in the response
        $response->assertJsonFragment(["Grilled Cheese and Veggie Jumble"]);
        $response->assertJsonFragment(["Mediterranean Baked Veggies"]);
    }
}
