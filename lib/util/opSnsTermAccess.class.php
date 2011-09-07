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
    return !is_null($this->offsetGet($offset));
  }

  public function offsetGet($name)
  {
    $fronting = false;

    if (preg_match('/[A-Z]/', $name[0]))
    {
      $fronting = true;
      $name = strtolower($name[0]).substr($name, 1);
    }

    $result = SnsTerm::find_by_name($name, array(
      'conditions' => array('application = ? and translation.culture = ?', self::$application, self::$culture),
//      'include' => array('translation'),
    ));

    if ($result && $fronting)
    {
      $result->fronting();
    }

    return $result;
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
