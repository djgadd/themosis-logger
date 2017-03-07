<?php
namespace Com\KeltieCochrane\Logger\Facades;

use Themosis\Facades\Facade;

class Log extends Facade
{
  /**
   * Return the igniterService key responsible for the ajax api.
   *
   * @return string
   */
  protected static function getFacadeAccessor()
  {
    return 'log';
  }
}
