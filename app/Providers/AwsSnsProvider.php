<?php

namespace App\Providers;

use App\Broadcasting\AwsSnsChannel;
use Aws\Sns\SnsClient;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification as LLNotification;

class AwsSnsProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        LLNotification::resolved(function (ChannelManager $service) {
            $service->extend('sns', function ($app) {
                return new AwsSnsChannel(
                    new SnsClient([
                        'region' => env('AWS_DEFAULT_REGION', 'eu-west-1'),
                        'version' => 'latest'
                    ])
                );
            });
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
