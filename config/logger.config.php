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
  | Production
  |--------------------------------------------------------------------------
  | Enabling this will wrap all handlers in a FingersCrossedHandler (so you won't
  | get verbose logging unless an error is triggered.)
  */
  'environment' => env('ENVIRONMENT', 'production'),

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
  'slack-username' => env('SLACK_USERNAME', 'Logger'),
];
