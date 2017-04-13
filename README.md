# Themosis Logger
A package for the Themosis framework that provides access to Monolog. Similar to the Laravel setup, provides support for log files, Loogly, and Slack. Also loads in some Laravel helper functions associated with logging. This plugin requires `keltiecochrane/themosis-illuminate`'s ConfigServiceProvider to be setup.

## Install
Install through composer: -
`composer require keltiecochrane/themosis-logger`

Copy the `logger.config.php` file to your `theme/resources/config` folder, and configure as appropriate.

Register the service provider in your `theme/resources/config/providers.php` file: -
`KeltieCochrane\Logger\LogServiceProvider::class,`

Optionally register the alias in your `theme/resources/config/theme.php` file: -
`'Log' => KeltieCochrane\Logger\LogFacade::class,`

## Examples
```
  Log::error('An error occurred', ['code' => 1, 'message' => 'Oops.']);
```

See the [Monolog docs](https://github.com/Seldaek/monolog/blob/master/doc/01-usage.md) for more info.

## Helpers
The following (additional) helpers are available: -

* info
* logger

See the [Laravel docs](https://laravel.com/docs/5.4/helpers) for more info.

## Support
This package is provided as is, though we'll endeavour to help where we can. By using this plugin you forfeit your right to any warranty or costs associated with it's use.

## Contributing
Any contributions would be encouraged and much appreciated, you can contribute by: -

* Reporting bugs
* Suggesting features
* Sending pull requests
