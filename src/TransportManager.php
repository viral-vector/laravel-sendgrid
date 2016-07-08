<?php

namespace ViralVector\LaravelSendgridDriver;

use ViralVector\LaravelSendgridDriver\Transport\SendgridTransport;

class TransportManager extends \Illuminate\Mail\TransportManager
{
    /**
     * Create an instance of the SendGrid Swift Transport driver.
     *
     * @return Transport\SendGridTransport
     */
    protected function createSendgridDriver()
    {
        $config = $this->app['config']->get('services.sendgrid', []);
        
        return new SendgridTransport($config['api_key']);
    }
}