# ChatOneToOne

## Required

Laravel version >= 11

https://laravel.com/docs/11.x/installation

Laravel Reverb

https://laravel.com/docs/11.x/reverb#main-content

Laravel Echo and Pusher

```bash
npm install --save-dev laravel-echo pusher-js
```

## Installation

Via Composer

```bash
composer require salvatorecervone/chatonetoone
```

## Publishs

Publish controller

```bash
php artisan vendor:publish --tag=controller-chatonetoone
```

Publish Js file and component

```bash
php artisan vendor:publish --tag=js-chatonetoone
```

Publish model

```bash
php artisan vendor:publish --tag=model-chatonetoone
```

Publish event

```bash
php artisan vendor:publish --tag=event-chatonetoone
```

## Insert Echo

After this add this line of code in your bootstrap.js file

```bash
import './chatonetoone-echo'
```

## Migrations

Call migrations

```bash
php artisan migration
```
## Usage

Start server websocket

```bash
php artisan reverb:start
```

Now you have Controllers, Models, Migrations and Vue components for Chat one to one

The components is locate in resources/js/Pages/

The component ChatComponent2 is the start component for Chat

## Security

If you discover any security related issues, please email salvatore999@virgilio.it instead of using the issue tracker.

## Credits

- [Salvatore Cervone]

## License

MIT. Please see the [license file](license.md) for more information.
[link-packagist]: https://packagist.org/packages/salvatorecervone/chatonetoone
