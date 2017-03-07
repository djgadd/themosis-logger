Themosis Juicer
===============

A WordPress plugin for the Themosis framework that provides a facade to access an
instance of Monolog. Uses a fingers crossed handler to verbosely log when errors
occur. Logs to file, Loggly and Slack.

Install
-------
Install through composer: -

`composer require keltiecochrane/themosis-logger`

Activate the plugin in WordPress then add it to your theme's class aliases in the theme.config.php file: -

```
  'aliases' => [
    ...
    'Log' => Com\KeltieCochrane\Logger\Facades\Log::class,
    ...
  ]
```

Create a logger.config.php, and add the following: -

```
return [
  // Channel can be a string or a closure
  'channel' => function () {
    return getenv('ENVIRONMENT');
  },
  'log-file' => 'logs/wordpress.log', // Will put the log file in storage/logs/wordpress.log
  'loggly-token' => '',
  'slack-token' => '',
  'slack-channel' => '#logs', // Will put logs in the #logs channel of your Slack
];
```

Usage
-----
Use the Log facade to access Monolog ([see docs](https://github.com/Seldaek/monolog/blob/master/doc/01-usage.md)), e.g.: -

```
Log::error('An error occurred', ['code' => 1, 'message' => 'Oops.']);
```

Todo
----
* Unit tests.
* Admin interface to view logs.


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
