<?php

namespace ViralVector\LaravelSendgridDriver;

use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\TransportManager;
use ViralVector\LaravelSendgridDriver\Transport\SendgridTransport;

class SendgridTransportServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function register()
    {
        $this->app->afterResolving(TransportManager::class, function(TransportManager $manager) {
            $this->extendTransportManager($manager);
        });
    }

    /**
     * @param \Illuminate\Mail\TransportManager $manager
     */
    public function extendTransportManager(TransportManager $manager)
    {
        $manager->extend('sendgrid', function() {
            $config = $this->app['config']->get('services.sendgrid', []);

            return new SendgridTransport($config['api_key']);
        });
    }
}
