# ChatOneToOne

## Required

Laravel version >= 11

Laravel Reverb

Laravel Echo and Pusher

```bash
npm install --save-dev laravel-echo pusher-js
```

## Installation

Via Composer

```bash
composer require salvatorecervone/chatonetoone
```

## Usage

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

After this add this line of code in your bootstrap.js file

```bash
import './chatonetoone-echo'
```

## Security

If you discover any security related issues, please email salvatore999@virgilio.it instead of using the issue tracker.

## Credits

- [Salvatore Cervone]

## License

MIT. Please see the [license file](license.md) for more information.
[link-packagist]: https://packagist.org/packages/salvatorecervone/chatonetoone
