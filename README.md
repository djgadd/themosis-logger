Themosis Juicer
===============

A ServiceProvider for Themosis that provides logging through Monolog. Similar to
the Laravel setup, provides support for log files (default), Loggly, and Slack.

Install
-------
Install through composer: -

`composer require keltiecochrane/themosis-logger`

Copy the `config/logger.config.php` to your `theme/resources/config` directory,
and configure as appropriate.

Register the service provider in your `theme/resources/config/providers.php` file: -

`KeltieCochrane\Logger\LogServiceProvider::class,`

Register the alias in your `theme/resources/config/theme.php` file: -

`'Log' => KeltieCochrane\Logger\LogFacade::class,`

Usage
-----

Use the facade to access the Monolog instance, for more info
[see their docs](https://github.com/Seldaek/monolog/blob/master/doc/01-usage.md),
eg.:-

```
Log::error('An error occurred', ['code' => 1, 'message' => 'Oops.']);
```

Support
-------
This plugin is provided as is, though we'll endeavour to help where we can. By
using this plugin you forfeit your right to any warranty or costs associated with
it's use.

Contributing
------------
Any contributions would be encouraged and much appreciated, you can contribute by: -

* Reporting bugs
* Suggesting features
* Sending pull requests
