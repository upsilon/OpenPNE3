<?php

class Navigation extends ActiveRecord\Model
{
  static $table_name = 'navigation';

  static $has_many = array(
    array(
      'translation',
      'foreign_key' => 'id', 'class' => 'NavigationTranslation', 'index_by' => 'lang',
    ),
  );

  public function get_caption()
  {
    return $this->translation['ja_JP']->caption;
  }
}
