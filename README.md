# sMailer for Evolution CMS 3
[![Latest Stable Version](https://img.shields.io/packagist/v/seiger/smailer?label=version)](https://packagist.org/packages/seiger/smailer)
[![CMS Evolution](https://img.shields.io/badge/CMS-Evolution-brightgreen.svg)](https://github.com/evolution-cms/evolution)
![PHP version](https://img.shields.io/packagist/php-v/seiger/smailer)
[![License](https://img.shields.io/packagist/l/seiger/smailer)](https://packagist.org/packages/seiger/smailer)
[![Issues](https://img.shields.io/github/issues/Seiger/smailer)](https://github.com/Seiger/smailer/issues)
[![Stars](https://img.shields.io/packagist/stars/Seiger/smailer)](https://packagist.org/packages/seiger/smailer)
[![Total Downloads](https://img.shields.io/packagist/dt/seiger/smailer)](https://packagist.org/packages/seiger/smailer)

**sMailer** Mailing lists Management Module for Evolution CMS admin panel.

## Install by artisan package installer

Run in you /core/ folder:

```console
php artisan package:installrequire seiger/smailer "*"
```

Generate the config file in **core/custom/config/cms/settings** with
name **smailer.php** the file should return a
comma-separated list of templates.

```console
php artisan vendor:publish --provider="Seiger\sMailer\sMailerServiceProvider"
```

Run make DB structure with command:

```console
php artisan migrate
```
