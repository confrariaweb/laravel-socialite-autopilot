<?php

namespace ConfrariaWeb\Youtube\Providers;

use ConfrariaWeb\Youtube\Services\YoutubeService;
use Illuminate\Support\ServiceProvider;

class YoutubeServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([__DIR__ . '/../../config/cw_youtube.php' => config_path('cw_youtube.php')], 'config');
    }

    public function register()
    {
        $this->app->bind('YoutubeService', function () {
            return new YoutubeService();
        });
    }
}
