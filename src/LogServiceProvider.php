<?php

namespace KeltieCochrane\Logger;

use Monolog\Logger;
use Monolog\Handler\SlackHandler;
use Monolog\Handler\LogglyHandler;
use Monolog\Handler\AbstractHandler;
use Monolog\Handler\RotatingFileHandler;
use Themosis\Foundation\ServiceProvider;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;

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
      $channel = $app['config']->get('logger.channel', 'production');

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
   * Wraps handlers in FingersCrossedHandler appropriate to environment
   *
   * @return \Monolog\Handler\FingersCrossedHandler
   */
  protected function fingersCrossedHandler(AbstractHandler $handler) : FingersCrossedHandler
  {
    return new FingersCrossedHandler($handler, new ErrorLevelActivationStrategy(app('config')->get('logger.level')));
  }

  /**
   * Creates a RotatingFileHandler
   *
   * @return \Monolog\Handler\FingersCrossedHandler
   */
  protected function createRotatingFileHandler() : FingersCrossedHandler
  {
    return $this->fingersCrossedHandler(new RotatingFileHandler(
      themosis_path('storage').$this->app['config']->get('logger.log-file'),
      $this->app['config']->get('logger.max-files')
    ));
  }

  /**
   * Determines whether the config enables LogglyHandler
   *
   * @return bool
   */
  protected function useLogglyHandler() : bool
  {
    return $this->app['config']->has('logger.loggly-token');
  }

  /**
   * Creates a LogglyHandler
   *
   * @return \Monolog\Handler\FingersCrossedHandler
   */
  protected function createLogglyHandler() : FingersCrossedHandler
  {
    return $this->fingersCrossedHandler(new LogglyHandler($this->app['config']->get('logger.loggly-token')));
  }

  /**
   * Determines whether the config enables SlackHandler
   *
   * @return bool
   */
  protected function useSlackHandler() : bool
  {
    return $this->app['config']->has('logger.slack-token');
  }

  /**
   * Creates a SlackHandler, adds a processor to the handler to indicate what
   * what website it came form (useful when multiple loggers are outputting in
   * the same channel)
   *
   * @param bool $useAttachment
   * @param string $iconEmoji
   * @param int $level
   * @param bool $bubble
   * @param bool $useShortAttachment
   * @param bool $includeContextAndExtra
   * @param array $excludeFields
   * @return \Monolog\Handler\FingersCrossedHandler
   */
  protected function createSlackHandler(bool $useAttachment = true, string $iconEmoji = null, int $level = Logger::DEBUG, bool $bubble = true, bool $useShortAttachment = false, bool $includeContextAndExtra = true, array $excludeFields = []) : FingersCrossedHandler
  {
    $handler = new SlackHandler(
      $this->app['config']->get('logger.slack-token'),
      $this->app['config']->get('logger.slack-channel', '#logs'),
      strtolower($this->app['config']->get('logger.slack-username', 'logger')),
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

    return $this->fingersCrossedHandler($handler);
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
