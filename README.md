PHP Trello API v2 client
========================

A simple Object Oriented wrapper for the Trello API, written in PHP7.4.

Uses [Trello API v1](https://trello.com/docs/index.html). The object API is very similar to the RESTful API.

## Features

* Follows PSR-0 conventions and coding standards: autoload friendly
* Light and fast thanks to lazy loading of API classes
* Extensively tested
* Tested in Symfony 6.2

## Requirements

	"require": {
		"php": ">=8.0",
		"symfony/event-dispatcher": "*",
		"league/oauth1-client": "*",
		"guzzlehttp/guzzle": "*"
	},

## Installation

The recommended way is using [composer](http://getcomposer.org):

```bash
$ composer require vasyaxy/php-trello-api
```
However, `php-trello-api` follows the PSR-0 naming conventions, which means you can easily integrate `php-trello-api` class loading in your own autoloader.

## Make Auth URL

```php
<?php

namespace App;

use Trello\Client;

class TrelloApi
{
    public Client|null $client;

    public function __construct()
    {
        $this->client = new Client();
    }
    
    public function setupTrelloClient(): void
    {
        $this->client->authenticate(
            'API_KEY',                            // Api key - get from https://dashboard.stripe.com/test/apikeys7
            'USER_TOKEN (empty)',                 // Empty for this exampe
            Client::AUTH_URL_CLIENT_ID            // nvm
        );
    }

    public function getAuthUrl(): string
    {
        $this->setupTrelloClient();

        return $this->client->getAuthUrl([
            'key' => 'API_KEY',                   // Api key - get from https://trello.com/power-ups/admin - NEW - get key
            'secret' => 'API_SECRET',             // ^^^ "Reveal test key"
            'callbackUrl' => '!!CALL_BACK_URL!!', // example: 'http://mymegatite.com/trello-hook/'
            'name' => 'My Mega Trello App!!!1',   // nvm
            'expiration' => 'never',              // >> MH <<
            'scope' => 'read,write',              // >> MH <<
        ]);
    }
}
```

## Basic usage

```php
use Trello\Client;

$client = new Client();
$client->authenticate('API_KEY', 'USER_KEY', Client::AUTH_URL_CLIENT_ID);
$boards = $client->api('member')->boards()->all();
```

The `$client` object gives you access to the entire Trello API.

## Advanced usage with the Trello manager

This package includes a simple model layer above the API with a nice chainable API allowing following manipulation of Trello objects:

```php
use Trello\Client;
use Trello\Manager;

$client = new Client();
$client->authenticate('API_KEY', 'USER_KEY', Client::AUTH_URL_CLIENT_ID);

$manager = new Manager($client);

$card = $manager->getCard('547440ad3f8b882bc11f0497');

$card
    ->setName('Test card')
    ->setDescription('Test description')
    ->save();
```

## Dispatching Trello events to your app

The service uses the [Symfony EventDispatcher](https://github.com/symfony/EventDispatcher) component to dispatch events occuring on incoming webhooks.

Take a look at the [Events](https://github.com/vasyaxy/php-trello-api/blob/master/lib/Trello/Events.php) class constants for names and associated event classes.

```php
use Trello\Client;
use Trello\Service;
use Trello\Events;

$client = new Client();
$client->authenticate('API_KEY', 'USER_KEY', Client::AUTH_URL_CLIENT_ID);

$service = new Service($client);

// Bind a callable to a given event...
$service->addListener(Events::BOARD_UPDATE, function ($event) {
    $board = $event->getBoard();

    // do something
});

// Check if the current request was made by a Trello webhook
// This will dispatch any Trello event to listeners defined above
$service->handleWebhook();
```

## Documentation
* Package [API](docs/Api/Index.md)
* Official [API documentation](https://trello.com/docs/index.html).

## Contributing

Feel free to make any comments, file issues or make pull requests.

## License

`php-trello-api` is licensed under the MIT License - see the LICENSE file for details

## Credits

- Forked by [vasyaxy/php-trello-api](https://github.com/vasyaxy/php-trello-api)
- Largely inspired by the excellent [php-github-api](https://github.com/KnpLabs/php-github-api) developed by the guys at [KnpLabs](http://knplabs.fr)
- Thanks to Trello for the API and documentation.
