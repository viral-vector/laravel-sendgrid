Laravel SendGrid Driver
====
Initialize or use an existing [Sendgrid api key](https://sendgrid.com/docs/User_Guide/Settings/api_keys.html).

```
$ composer require viral-vector/laravel-sendgrid-driver:dev-master
```

```php
'providers' => [
    ViralVector\LaravelSendgridDriver\SendgridTransportServiceProvider::class,
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