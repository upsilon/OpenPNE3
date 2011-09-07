<?php

class SnsConfig extends ActiveRecord\Model
{
  static $table_name = 'sns_config';

  static public function get($name, $default = null)
  {
    $config = self::find_by_name($name);

    if (null === $config)
    {
      return $default;
    }

    return $config->value;
  }
}
