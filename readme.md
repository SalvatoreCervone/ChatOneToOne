# Real time Chat One To One

## Required

Laravel version >= 11

https://laravel.com/docs/11.x/installation

Laravel Reverb

https://laravel.com/docs/11.x/reverb#main-content

Carbon

https://carbon.nesbot.com/

Moment

https://momentjs.com/

For use actual css and graph install

Prime vue

https://primevue.org/vite/

Prime vue icons

https://primevue.org/icons/

## Installation

Via Composer

```bash
composer require salvatorecervone/chatonetoone
```

## Publishs

If you would publish for change normal use of:

Controllers
Models

you use:

Publish controller

```bash
php artisan vendor:publish --tag=controller-chatonetoone
```

Publish model

```bash
php artisan vendor:publish --tag=model-chatonetoone
```

### Its obligatory

Publish vue component

```bash
php artisan vendor:publish --tag=vue-chatonetoone
```

Publish event

```bash
php artisan vendor:publish --tag=event-chatonetoone
```

Publish migration

```bash
php artisan vendor:publish --tag=migration-chatonetoone
```

## Migrations

Call migrations

```bash
php artisan migrate
```

## Usage

Start server websocket

```bash
php artisan reverb:start
```

Now you have Controllers, Models, Migrations and Vue components for Chat one to one

The components is locate in resources/js/Pages/

The component ChatComponent is the start component for Chat

Go to route {APP_URL}/chats

and view chat run

## Remember

In production change ip in file .env

```bash
KEY -> REVERB_HOST
```

with the ip where application websocket installed

if the server is one, for application and chat, you would write new env parameter

```bash
APP_URL_NO_SCHEMA=127.0.0.1
```

and apply with

```bash
REVERB_HOST="${APP_URL_NO_SCHEMA}"
```

## Security

If you discover any security related issues, please email salvatore999@virgilio.it instead of using the issue tracker.

## Credits

- [Salvatore Cervone]

## License

MIT. Please see the [license file](license.md) for more information.
[link-packagist]: https://packagist.org/packages/salvatorecervone/chatonetoone
