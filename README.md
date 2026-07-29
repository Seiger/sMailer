# sMailer for Evolution CMS 3
[![Latest Stable Version](https://img.shields.io/packagist/v/seiger/smailer?label=version)](https://packagist.org/packages/seiger/smailer)
[![CMS Evolution](https://img.shields.io/badge/CMS-Evolution-brightgreen.svg)](https://github.com/evolution-cms/evolution)
![PHP version](https://img.shields.io/packagist/php-v/seiger/smailer)
[![License](https://img.shields.io/packagist/l/seiger/smailer)](https://packagist.org/packages/seiger/smailer)
[![Issues](https://img.shields.io/github/issues/Seiger/smailer)](https://github.com/Seiger/smailer/issues)
[![Stars](https://img.shields.io/packagist/stars/Seiger/smailer)](https://packagist.org/packages/seiger/smailer)
[![Total Downloads](https://img.shields.io/packagist/dt/seiger/smailer)](https://packagist.org/packages/seiger/smailer)

**sMailer** provides mailing-list and newsletter management for Evolution CMS.

The `2.x` branch currently contains the installable EvoUI manager foundation.
Subscriber, campaign, delivery queue, and sTask worker capabilities will be
introduced as separate verified increments.

## Install by artisan package installer

Run in your `/core/` folder:

```console
php artisan package:installrequire seiger/smailer "2.x-dev"
```

The foundation requires PHP 8.4 or newer, Evolution CMS 3.5.7 or newer, EvoUI,
and sTask. It registers the manager shell without publishing package-owned
copies of EvoUI assets and without enabling delivery workers or the historical
cron-based delivery commands.

```console
composer test
```
