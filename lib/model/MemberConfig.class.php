<?php

class MemberConfig extends ActiveRecord\Model
{
  static $table_name = 'member_config';

  static $belongs_to = array(
    'member',
  );

  public function set_value_datetime($value)
  {
    $this->assign_attribute('value', $value);
    $this->assign_attribute('value_datetime', $value);

    return $value;
  }
}
