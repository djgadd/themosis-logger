<?php

return [
  /*
  |--------------------------------------------------------------------------
  | Channel
  |--------------------------------------------------------------------------
  | The channel to log to (this is unrelated to the slack-channel key)
  */
  'channel' => getenv('ENVIRONMENT'),

  /*
  |--------------------------------------------------------------------------
  | Production
  |--------------------------------------------------------------------------
  | Enabling this will wrap all handlers in a FingersCrossedHandler (so you won't
  | get verbose logging unless an error is triggered.)
  */
  'environment' => getenv('ENVIRONMENT'),

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
  'loggly-token' => '',

  /*
  |--------------------------------------------------------------------------
  | SlackHandler
  |--------------------------------------------------------------------------
  | Your Slack API token and channel, unset or leave blank to ignore
  */
  'slack-token' => '',
  'slack-channel' => '#logs',
];
