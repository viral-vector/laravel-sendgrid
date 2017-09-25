<?php

namespace ViralVector\LaravelSendgridDriver\Transport;

use Illuminate\Mail\Transport\Transport;

use Swift_Attachment;
use Swift_Mime_SimpleMessage;
use Swift_Events_EventListener;

use SendGrid;
use SendGrid\Mail;
use SendGrid\Email;
use SendGrid\Content;
use SendGrid\Personalization;

class SendgridTransport extends Transport
{
    const SMTP_API_NAME = 'sendgrid/x-smtpapi';

    protected $api_key;
    protected $message;
    
    /**
     * List of executable methods
     *
     * @var $methods
     */
    private $methods = [
        'setTo',
        'setCc',
        'setBcc',
        'setFrom',
        'setSubject',
        'setContent',
    ];

    /**
     *
     */
    public function __construct($api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {   
        $this->message = new Mail(
            null,
            null,
            null,
            null
        );

        if(!isset($this->message->personalizations[0]))
        {
            $this->message->addPersonalization(new Personalization());
        }

        foreach ($this->methods as $method)
        {
            if(method_exists($this, $method))
            {
                call_user_func([$this, $method], $message);
            }
        }
        
        return $this->pushEmail();
    }

     /**
      * 
      * @param  Swift_Mime_SimpleMessage $message
      */
    protected function setTo(Swift_Mime_SimpleMessage $message)
    {  
        if ($data = $message->getTo()) 
        {   
            foreach ($data as $key => $value) 
            {
                $this->message->personalizations[0]->addTo(new Email($value, $key));
            }  
        }
    }

    /**
     * 
     * @param  Swift_Mime_SimpleMessage $message
     */
    protected function setFrom(Swift_Mime_SimpleMessage $message)
    {   
        if ($data = $message->getFrom()) 
        {
            foreach ($data as $key => $value) 
            {
                $this->message->setFrom(new Email($value, $key));   
            }           
        }
    }

    /**
      *
      * @param  Swift_Mime_SimpleMessage $message
      */
    protected function setCc(Swift_Mime_SimpleMessage $message)
    {
        if ($data = $message->getCc())
        {
            foreach ($data as $key => $value)
            {
                $this->message->personalizations[0]->addCc(new Email($value, $key));
            }
        }
    }

    /**
      *
      * @param  Swift_Mime_SimpleMessage $message
      */
    protected function setBcc(Swift_Mime_SimpleMessage $message)
    {
        if ($data = $message->getBcc())
        {
            foreach ($data as $key => $value)
            {
                $this->message->personalizations[0]->addBcc(new Email($value, $key));
            }
        }
    }

    /**
     * 
     * @param  Swift_Mime_SimpleMessage $message
     */
    protected function setSubject(Swift_Mime_SimpleMessage $message)
    {
        if ($data = $message->getSubject()) 
        {
            $this->message->personalizations[0]->setSubject($data);    
        }
    }

    /**
     * 
     * @param  Swift_Mime_SimpleMessage $message
     */
    protected function setContent(Swift_Mime_SimpleMessage $message)
    {   
        if ($data = $message->getBody()) 
        {
            $this->message->addContent(new Content('text/html', $data));
        }
    }

    /**
     *
     * @param  Swift_Mime_SimpleMessage $message
     */
    protected function setAttachment(Swift_Mime_SimpleMessage $message)
    {
        if ($data = $message->getChildren())
        {
            foreach ($data as $attachment)
            {
                if (! ($attachment instanceof Swift_Attachment) )
                {
                    continue;
                }

                // --- @TODO:: Add attachment
            }
        }
    }

    /**
     * Get a new sendgrid instance.
     *
     * @return SendGrid
     */
    protected function getSendgrid()
    {   
        return new SendGrid($this->api_key);
    }

    /**
     * Send email to sendgrid
     *
     * @return HTTP
     */
    protected function pushEmail()
    {   
        if(is_null($this->message))
        {
            return null;
        }

        return $this->getSendgrid()->client->mail()->send()->post($this->message);
    }

    /**
     * {@inheritdoc}
     */
    public function isStarted()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function stop()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function registerPlugin(Swift_Events_EventListener $plugin)
    {

    }
}