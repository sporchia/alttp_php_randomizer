<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('randomizer', 'RandomizerController@generateSeed')->middleware('throttle:150,360');

Route::post('randomizer/spoiler', 'RandomizerController@testGenerateSeed')->middleware('throttle:300,360');

Route::post('customizer', 'CustomizerController@generateSeed')->middleware('throttle:50,360');

Route::post('customizer/test', 'CustomizerController@testGenerateSeed')->middleware('throttle:200,360');

Route::get('daily', static function () {
    $featured = ALttP\FeaturedGame::today();
    if (!$featured) {
        $exitCode = Artisan::call('alttp:dailies', ['days' => 1]);
        $featured = ALttP\FeaturedGame::today();
    }
    $seed = $featured->seed;
    if ($seed) {
        return [
            'hash' => $seed->hash,
            'daily' => $featured->day,
        ];
    }
    abort(404);
});