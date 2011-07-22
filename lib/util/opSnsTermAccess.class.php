<?php

class opSnsTermAccess implements ArrayAccess
{
  static protected
    $culture = null,
    $application = null;

  static public function configure($culture = '', $application = '')
  {
    if ($culture)
    {
      self::$culture = $culture;
    }

    if ($application)
    {
      self::$application = $application;
    }
  }

  public function offsetExists($offset)
  {
    return !is_null(SnsTermPeer::get($offset, self::$application, self::$culture));
  }

  public function offsetGet($offset)
  {
    return SnsTermPeer::get($offset, self::$application, self::$culture);
  }

  public function offsetSet($offset, $value)
  {
    throw new LogicException('The SnsTermTable class is not writable.');
  }

  public function offsetUnset($offset)
  {
    throw new LogicException('The SnsTermTable class is not writable.');
  }
}
