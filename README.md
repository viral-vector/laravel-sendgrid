Laravel SendGrid Driver
====
Initialize or use an existing [Sendgrid api key](https://sendgrid.com/docs/User_Guide/Settings/api_keys.html).

```
$ composer require viral-vector/laravel-sendgrid-driver:dev-master
```

Remove the default mail service provider and add the sendgrid service provider in config/app.php:
```php
'providers' => [
    Illuminate\Mail\MailServiceProvider::class,

    ViralVector\LaravelSendgridDriver\MailServiceProvider::class,
];
```

.env
```
MAIL_DRIVER=sendgrid

SENDGRID_API_KEY='....'
```

config/service.php
```
'sendgrid' => [
    'api_key' => env('SENDGRID_API_KEY')
]
```