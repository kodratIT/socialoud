<?php

use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'FriendsOfBotble\GoogleReviews\Http\Controllers'], function (): void {
    AdminHelper::registerRoutes(function (): void {
        // Reviews CRUD routes
        Route::group(['prefix' => 'google-reviews', 'as' => 'google-reviews.'], function (): void {
            Route::resource('', 'GoogleReviewDataController')
                ->parameters(['' => 'googleReview'])
                ->except(['show']);
        });

        // Settings routes
        Route::group(['prefix' => 'settings'], function (): void {
            Route::get('google-reviews', [
                'as' => 'google-reviews.settings',
                'uses' => 'Settings\GoogleReviewsSettingController@edit',
                'permission' => 'google-reviews.settings',
            ]);

            Route::put('google-reviews', [
                'as' => 'google-reviews.settings.update',
                'uses' => 'Settings\GoogleReviewsSettingController@update',
                'permission' => 'google-reviews.settings',
            ]);
        });
    });
});
