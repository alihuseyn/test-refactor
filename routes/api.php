<?php

$app->group(['prefix'=>'api', 'middleware'=> ['auth','permission']], function () use ($app) {
    $app->group(['prefix'=>'v1'], function () use ($app){
        /***
         * Translation agencies
         */
        // List of all request which wait for translation
        $app->get('/waiting', 'WaitingController@retrieve');
        // Translated content enter endpoint
        $app->post('/translate', 'TranslateController@add');

        /***
         * Internal applications
         */
        // Get all translations
        $app->get('/value', 'ValueController@get');
        // Get given value translation information
        $app->get('/value/{value}', 'ValueController@get');
        // New request enter endpoint
        $app->post('/value', 'ValueController@add');
    });
});
