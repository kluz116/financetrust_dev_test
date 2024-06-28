<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;

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

Route::get('/aggregated-data', [RecipeController::class, 'aggregatedData']);
Route::get('/unique-recipe-count', [RecipeController::class, 'uniqueRecipeCount']);
Route::get('/count-per-recipe', [RecipeController::class, 'countPerRecipe']);
Route::get('/busiest-postcode', [RecipeController::class, 'busiestPostcode']);
Route::get('/match-by-name', [RecipeController::class, 'matchByName']);
