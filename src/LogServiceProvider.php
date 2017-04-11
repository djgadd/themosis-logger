<?php

namespace KeltieCochrane\Logger;

use Monolog\Logger;
use Themosis\Facades\Config;
use Monolog\Handler\SlackHandler;
use Monolog\Handler\LogglyHandler;
use Monolog\Handler\AbstractHandler;
use Monolog\Handler\RotatingFileHandler;
use Themosis\Foundation\ServiceProvider;
use Monolog\Handler\FingersCrossedHandler;

class LogServiceProvider extends ServiceProvider
{
  /**
   * Defer loading unless we need it, saves us a little bit of overhead if the
   * current request isn't trying to log anything.
   *
   * @var bool
   */
  protected $defer = true;

  /**
   * {@inheritdoc}
   */
  public function register()
  {
    $this->app->singleton('log', function ($app) {
      $channel = Config::get('logger.channel') ?: 'production';

      // Create a logger
      $logger = new Logger($channel);

      // Push our rotating file handler (we always use this)
      $logger->pushHandler($this->createRotatingFileHandler());

      // If we have loggly push that
      if ($this->useLogglyHandler()) {
        $logger->pushHandler($this->createLogglyHandler());
      }

      // If we have slack push that
      if ($this->useSlackHandler()) {
        $logger->pushHandler($this->createSlackHandler());
      }

      return $logger;
    });
  }

  /**
   * Determines whether we're in production mode
   *
   * @return bool
   */
  protected function inProduction() : bool
  {
    // Check if we're in production, if we don't have a value return true anyway
    if (Config::get('logger.environment') === 'production' || empty(Config::get('logger.environment'))) {
      return true;
    }

    return false;
  }

  /**
   * If we're inProduction then wrap the handler in a FingersCrossedHandler
   *
   * @return \Monolog\Handler\AbstractHandler
   */
  protected function productionHandler(AbstractHandler $handler) : AbstractHandler
  {
    if ($this->inProduction()) {
      return new FingersCrossedHandler($handler);
    }

    return $handler;
  }

  /**
   * Creates a RotatingFileHandler
   *
   * @return \Monolog\Handler\AbstractHandler
   */
  protected function createRotatingFileHandler() : AbstractHandler
  {
    return $this->productionHandler(new RotatingFileHandler(
      themosis_path('storage').Config::get('logger.log-file'),
      Config::get('logger.max-files')
    ));
  }

  /**
   * Determines whether the config enables LogglyHandler
   *
   * @return bool
   */
  protected function useLogglyHandler() : bool
  {
    return !empty(Config::get('logger.loggly-token'));
  }

  /**
   * Creates a LogglyHandler
   *
   * @return \Monolog\Handler\AbstractHandler
   */
  protected function createLogglyHandler() : AbstractHandler
  {
    return $this->productionHandler(new LogglyHandler(Config::get('logger.loggly-token')));
  }

  /**
   * Determines whether the config enables SlackHandler
   *
   * @return bool
   */
  protected function useSlackHandler() : bool
  {
    return !empty(Config::get('logger.slack-token')) && !empty(Config::get('logger.slack-channel'));
  }

  /**
   * Creates a SlackHandler, adds a processor to the handler to indicate what
   * what website it came form (useful when multiple loggers are outputting in
   * the same channel)
   *
   * @param string $username
   * @param bool $useAttachment
   * @param string $iconEmoji
   * @param int $level
   * @param bool $bubble
   * @param bool $useShortAttachment
   * @param bool $includeContextAndExtra
   * @param array $excludeFields
   * @return \Monolog\Handler\AbstractHandler
   */
  protected function createSlackHandler(string $username = 'Logger', bool $useAttachment = true, string $iconEmoji = null, int $level = Logger::DEBUG, bool $bubble = true, bool $useShortAttachment = false, bool $includeContextAndExtra = true, array $excludeFields = []) : AbstractHandler
  {
    $handler = new SlackHandler(
      Config::get('logger.slack-token'),
      Config::get('logger.slack-channel'),
      $username,
      $useAttachment,
      $iconEmoji,
      $level,
      $bubble,
      $useShortAttachment,
      $includeContextAndExtra,
      $excludeFields
    );

    // Add a processor for slack so we know what website the message came from
    $handler->pushProcessor(function ($record) {
      $record['extra']['Website'] = get_bloginfo('name');
      return $record;
    });

    return $this->productionHandler($handler);
  }

  /**
   * {@inheritdoc}
   */
  public function provides()
  {
    return [
      'log',
    ];
  }
}
