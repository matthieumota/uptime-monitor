UptimeMonitor Component
=======================

![Tests](https://github.com/matthieumota/uptime-monitor/workflows/Tests/badge.svg)

The UptimeMonitor component provides PHP Object Oriented library to easily monitor websites.

## Installation

UptimeMonitor uses Composer.

```
composer require matthieumota/uptime-monitor
```

## Usage

```php
<?php

require __DIR__.'/vendor/autoload.php';

use MatthieuMota\Component\UptimeMonitor\UptimeMonitor;

$uptimeMonitor = new UptimeMonitor();

$uptimeMonitor
    ->add('https://boxydev.com')
    ->add('https://matthieumota.fr')
    ->add('https://domain.notexists')
    ->add('https://expired.badssl.com')
;

// Simply stdout result at the moment
$uptimeMonitor->check();

```

## Notes

This component is work in progress, it's no stable. Also, you can contribute, a PR well documented and tested can be merged.
