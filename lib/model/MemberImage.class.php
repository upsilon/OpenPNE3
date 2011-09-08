<?php

class MemberImage extends ActiveRecord\Model
{
  static $table_name = 'member_image';

  static $belongs_to = array(
    array(
      'file',
      'primary_key' => 'file_id', 'class' => 'File',
    ),
  );
}
