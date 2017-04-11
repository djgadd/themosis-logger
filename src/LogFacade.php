<?php
namespace KeltieCochrane\Logger;

use Themosis\Facades\Facade;

class Log extends Facade
{
  /**
   * Return the key the logger is bound to
   * @return string
   */
  protected static function getFacadeAccessor()
  {
    return 'log';
  }
}
