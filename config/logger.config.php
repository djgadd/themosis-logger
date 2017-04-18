<?php

return [
  /*
  |--------------------------------------------------------------------------
  | Channel
  |--------------------------------------------------------------------------
  | The channel to log to (this is unrelated to the slack-channel key)
  */
  'channel' => env('ENVIRONMENT', 'production'),

  /*
  |--------------------------------------------------------------------------
  | level
  |--------------------------------------------------------------------------
  | Use the defined monolog levels to decide what level you would like the fingers
  | crossed handler to trigger at.
  */
  'level' => env('LOG_LEVEL', 'error'),

  /*
  |--------------------------------------------------------------------------
  | RotatingFileHandler
  |--------------------------------------------------------------------------
  | The filename to log to and maximum number of files to log.
  */
  'log-file' => 'logs/wordpress.log',
  'max-files' => 60,

  /*
  |--------------------------------------------------------------------------
  | LogglyHandler
  |--------------------------------------------------------------------------
  | Your loggly API token, unset or leave blank to ignore
  */
  'loggly-token' => env('LOGGLY_TOKEN'),

  /*
  |--------------------------------------------------------------------------
  | SlackHandler
  |--------------------------------------------------------------------------
  | Your Slack API token, channel, and bot username, unset or leave blank to
  | to disable. Your slack-username key can be anything you like, if you have
  | multiple websites dumping logs into the same channel we'd recommend setting
  | it to something to specific to the site rather than the generic 'Logger'
  | default
  */
  'slack-token' => env('SLACK_TOKEN'),
  'slack-channel' => env('SLACK_CHANNEL', '#logs'),
  'slack-username' => env('SLACK_USERNAME', 'logger'),
];
