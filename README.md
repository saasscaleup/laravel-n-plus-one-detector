![Laravel N+1 Query Detector](https://github.com/saasscaleup/laravel-n-plus-one-detector/blob/master/nplusone-saasscaleup.png?raw=true)

<h1 align="center">Real-time detection and resolution of N+1 query issues for Laravel applications. Provides detailed insights, advanced notifications, and a rich admin dashboard. </h1>
<h4 align="center">Perfect for solo developers and teams. Compatible with Laravel 5.5+ and PHP 7+.</h4>

<h4 align="center">
  <a href="https://youtube.com/@ScaleUpSaaS">Youtube</a>
  <span> · </span>
  <a href="https://twitter.com/ScaleUpSaaS">Twitter</a>
  <span> · </span>
  <a href="https://facebook.com/ScaleUpSaaS">Facebook</a>
  <span> · </span>
  <a href="https://buymeacoffee.com/scaleupsaas">By Me a Coffee</a>
</h4>

<p align="center">
   <a href="https://packagist.org/packages/saasscaleup/laravel-n-plus-one-detector">
      <img src="https://poser.pugx.org/saasscaleup/laravel-n-plus-one-detector/v/stable.png" alt="Latest Stable Version">
  </a>

  <a href="https://packagist.org/packages/saasscaleup/laravel-n-plus-one-detector">
      <img src="https://poser.pugx.org/saasscaleup/laravel-n-plus-one-detector/downloads.png" alt="Total Downloads">
  </a>

  <a href="https://packagist.org/packages/saasscaleup/laravel-n-plus-one-detector">
    <img src="https://poser.pugx.org/saasscaleup/laravel-n-plus-one-detector/license.png" alt="License">
  </a>
</p>

<br>

![banner](https://github.com/saasscaleup/laravel-n-plus-one-detector/blob/master/dashboard.png?raw=true)
<br>
<hr></hr>

# Laravel N+1 Query Detector

Laravel N+1 Query Detector is a powerful package designed to help you identify and resolve N+1 query problems in real-time. Perfect for individual developers and teams, this package enhances your application’s performance by catching inefficient queries before they impact your users.


## ✨ Features

- **Real-time N+1 Query Detection**: Identify N+1 queries as they happen, ensuring your application remains performant.
- **Detailed Query Insights**: Get detailed information about each detected N+1 query, including file line, the class and methods involved.
- **Advanced Notifications**: Receive alerts via Slack, webhooks, or email, ensuring you never miss an important notification.
- **Rich Admin Dashboards**: View all N+1 warnings in a comprehensive and user-friendly dashboard.
- **Suit for Teams and Solo Developers**: Designed to be used by both solo developers and teams working collaboratively.


## Requirements

 - PHP >= 7
 - Laravel >= 5.5

## Installation

### Install composer package (dev)

Via Composer - Not recommended for production environment

``` bash
composer require --dev saasscaleup/laravel-n-plus-one-detector
```

---

### Publish package's config, migration and view files

Publish package's config, migration and view files by running below command:

```bash
php artisan vendor:publish --provider="SaasScaleUp\NPlusOneDetector\NPlusOneDetectorServiceProvider"
```

### Run migration command

Run `php artisan migrate` to create `nplusone_warnings` table.

```bash
php artisan migrate
```


## Configuration

You can configure the package by editing the `config/n-plus-one.php` file. This file allows you to set the threshold for detecting N+1 queries, notification preferences, and more.

```php
<?php

return [
    
    // Whether or not to enable the N+1 Detector
    'enabled' => env('NPLUSONE_ENABLED', true),
    
    // The number of queries below which no alert will be triggered
    'queries_threshold' => env('NPLUSONE_QUERIES_THRESHOLD', 50),
    
    // The number of queries below which no detector will be triggered
    'detector_threshold' => env('NPLUSONE_DETECTOR_THRESHOLD', 10),
    
    // The number in minutes a n+1 query will be stored in memory before being discarded. So it won't repeat itself
    'cache_lifetime' => env('NPLUSONE_CACHE_LIFETIME', 14400), // 10 days
      
    // Slack webhook url for N + 1 Detector
    'slack_webhook_url' => env('NPLUSONE_SLACK_WEBHOOK_URL', ''),

    // Custom webhook url for N + 1 Detector
    'custom_webhook_url' => env('NPLUSONE_CUSTOM_WEBHOOK_URL', ''),

    // notification email address for N + 1 Detector
    'notification_email' => env('NPLUSONE_NOTIFICATION_EMAIL', 'admin@example.com'), // also possible: 'admin@example.com,admin2@example.com'

    // notification email subject for N + 1 Detector
    'notification_email_subject' => env('NPLUSONE_NOTIFICATION_EMAIL_SUBJECT', 'N+1 Detector Notification'),

    // Dashboard Middleware for N + 1 Detector
    'dashboard_middleware' => env('NPLUSONE_DASHBOARD_MIDDLEWARE', ['web', 'auth']),

    // Dashboard Pagination for N + 1 Detector
    'dashboard_records_pagination' => env('NPLUSONE_DASHBOARD_RECORDS_PAGINATION', 10),

];
```

## Usage

### Real-time Detection

The package automatically listens to your database queries and detects N+1 issues in real-time. When an N+1 query is detected, it logs the query details and optionally sends notifications.

### Admin Dashboard

Access the rich admin dashboard to view all N+1 warnings:

```php
Route::get('/n-plus-one-dashboard', [NPlusOneDashboardController::class, 'index'])->name('n-plus-one.dashboard');
```

The dashboard provides a comprehensive view of all detected N+1 queries, including SQL statements, occurrences, locations, and suggested fixes.

![banner](https://github.com/saasscaleup/laravel-n-plus-one-detector/blob/master/dashboard.png?raw=true)


### Notifications

Configure notifications to be sent via **Slack**, **webhook**, or **email**. Set your notification preferences in the `config/n-plus-one.php` file to stay informed about N+1 issues in your application.

```php
    // Slack webhook url for N + 1 Detector
    'slack_webhook_url' => env('NPLUSONE_SLACK_WEBHOOK_URL', ''),

    // Custom webhook url for N + 1 Detector
    'custom_webhook_url' => env('NPLUSONE_CUSTOM_WEBHOOK_URL', ''),

    // notification email address for N + 1 Detector
    'notification_email' => env('NPLUSONE_NOTIFICATION_EMAIL', 'admin@example.com'), // also possible: 'admin@example.com,admin2@example.com'
```

### Slack notification
![slack](https://github.com/saasscaleup/laravel-n-plus-one-detector/blob/master/slack1-notification.png?raw=true)
### Webhook notification
![webhook](https://github.com/saasscaleup/laravel-n-plus-one-detector/blob/master/webhook-notification.png?raw=true)
### Email notification
![email](https://github.com/saasscaleup/laravel-n-plus-one-detector/blob/master/email-notification.png?raw=true)


## Advanced Features

Detailed Query Insights

The package provides detailed insights into each detected N+1 query, including the class and methods involved. This helps you quickly pinpoint the source of the problem and implement a fix.

## License

Please see the [MIT](license.md) for more information.


## Support 🙏😃
  
 If you Like the tutorial and you want to support my channel so I will keep releasing amzing content that will turn you to a desirable Developer with Amazing Cloud skills... I will realy appricite if you:
 
 1. Subscribe to our [youtube](http://www.youtube.com/@ScaleUpSaaS?sub_confirmation=1)
 2. Buy me A [coffee ❤️](https://www.buymeacoffee.com/scaleupsaas)

Thanks for your support :)

<a href="https://www.buymeacoffee.com/scaleupsaas"><img src="https://img.buymeacoffee.com/button-api/?text=Buy me a coffee&emoji=&slug=scaleupsaas&button_colour=FFDD00&font_colour=000000&font_family=Cookie&outline_colour=000000&coffee_colour=ffffff" /></a>


