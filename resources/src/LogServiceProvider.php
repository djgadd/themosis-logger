<?php

namespace Com\KeltieCochrane\Logger;

use Monolog\Logger;
use Themosis\Facades\Config;
use Monolog\Handler\SlackHandler;
use Monolog\Handler\LogglyHandler;
use Monolog\Handler\RotatingFileHandler;
use Themosis\Foundation\ServiceProvider;
use Monolog\Handler\FingersCrossedHandler;

class LogServiceProvider extends ServiceProvider
{
  /**
   * Defer loading unless we need it, saves us a little bit of overhead if the
   * current request isn't trying to log anything.
   * @var bool
   */
  protected $defer = true;

  public function register()
  {
    $this->app->singleton('log', function ($app) {
      $channel = Config::get('logger.channel') ?: 'production';

      if (is_callable($channel)) {
        $channel = $channel();
      }

      // Create a logger
      $logger = new Logger($channel);

      // Setup a rotating log file
      $logger->pushHandler(new FingersCrossedHandler(
        new RotatingFileHandler(
          themosis_path('storage').Config::get('logger.log-file'),
          Config::get('logger.log-files')
        )
      ));

      // Setup loggly handler if we have it
      if (Config::get('logger.loggly-token')) {
        $logger->pushHandler(new FingersCrossedHandler(
          new LogglyHandler(Config::get('logger.loggly-token'))
        ));
      }

      // Setup slack if we have it
      if (Config::get('logger.slack-token') && Config::get('logger.slack-channel')) {
        $slackHandler = new SlackHandler(
          Config::get('logger.slack-token'),
          Config::get('logger.slack-channel'),
          'Logger',
          true,
          null,
          Logger::DEBUG,
          true,
          false,
          true
        );

        // Add a processor for slack so we know what website the message came from
        $slackHandler->pushProcessor(function ($record) {
          $record['extra']['Website'] = esc_url(home_url());
          return $record;
        });

        $logger->pushHandler(
          new FingersCrossedHandler($slackHandler)
        );
      }

      return $logger;
    });
  }
}
