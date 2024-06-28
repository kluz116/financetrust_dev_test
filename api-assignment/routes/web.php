<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
/*
Route::get('/api/unique-recipe-count', [RecipeController::class, 'uniqueRecipeCount']);
Route::get('/api/count-per-recipe', [RecipeController::class, 'countPerRecipe']);
Route::get('/api/busiest-postcode', [RecipeController::class, 'busiestPostcode']);
Route::get('/api/match-by-name', [RecipeController::class, 'matchByName']);
*/
