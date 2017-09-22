<?php

namespace ViralVector\LaravelSendgridDriver;

class MailServiceProvider extends \Illuminate\Mail\MailServiceProvider
{
    /**
     * Register the Swift Transport instance.
     *
     * @return void
     */
    protected function registerSwiftTransport()
    {
        $this->app['swift.transport'] = $this->app->bind(function ($app) 
        {
            return new TransportManager($app);
        });
    }
}
