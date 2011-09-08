<?php

class MemberProfile extends ActiveRecord\Model
{
  static $table_name = 'member_profile';

  public function __toString()
  {
    return (string)$this->value;
  }
}
