<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
/*
Route::get('/api/unique-recipe-count', [RecipeController::class, 'uniqueRecipeCount']);
Route::get('/api/count-per-recipe', [RecipeController::class, 'countPerRecipe']);
Route::get('/api/busiest-postcode', [RecipeController::class, 'busiestPostcode']);
Route::get('/api/match-by-name', [RecipeController::class, 'matchByName']);
*/