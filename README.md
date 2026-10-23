# LARANONCE

- [About](#About)
- [Installation](#Installation)
- [Setup](#Setup)
- [Configuration](#Configuration)
- [Usage](#Usage)
- [Verifying Nonce](#Verifying-Nonce)
- [Clean Up Expired Nonces](#Clean-Up-Expired-Nonces)

## About

Laranonce is a nonce generator package for your laravel applications.

## Installation

Install via composer `composer require njdigitallab/laranonce`.

## Setup

Publish the package migration file with `php artisan vendor:publish --tag=laranonce-migrations` then run the `php artisan migrate` command.

## Configuration

By default, nonces are valid for 900 seconds (15 minutes). To change this, add the `Laranonce::setLifetime()` method to your AppServiceProvider register method. It accepts one parameter which is the expiration time in seconds.

```php
use NjDigitalLab\Laranonce\Laranonce;

/**
 * Register any application services.
 */
public function register(): void
{
    Laranonce::setLifetime(43200); // 12 hours
}
```

## Usage

To generate the nonce value, call the `Laranonce::generate()` method. It accepts one parameter, which is the name of the action.

```php
<input type='hidden' name='nonce' value="{{ Laranonce::generate('submit_paypal_payment') }}">
```

If you want to generate the input markup, call the `Laranonce::generateField()` method. It accepts two parameters, which is the name of the action and the field name. The field name is optional and defaults to `nonce`.

```php
{!! Laranonce::generateField('submit_paypal_payment') !!}

// Output
<input type='hidden' name='nonce' value="{{ Laranonce::generate('submit_paypal_payment') }}">
```

## Verifying Nonce

To verify your nonce, call the `Laranonce::verify()` method. It accepts two parameters, which is the action and the submitted nonce.

```php
use NjDigitalLab\Laranonce\Laranonce;

public function processPayment(Request $request)
{
    if (Laranonce::verify('submit_paypal_payment', $request->nonce)) {
        // Nonce is valid
    }
}
```

Once a nonce has passed true, or is expired, it is automatically purged from the system to ensure any subsequent request is invalid.

## Clean Up Expired Nonces

After time, unchecked nonces will expire and will take up space in your database. To clean them up, take advantage of task scheduling.

```php
use App\Console\Commands\SendEmailsCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command('laranonce:prune')->daily();
```

See [Laravel Docs](https://laravel.com/framework/docs/13.x/scheduling) for more information on task scheduling.
