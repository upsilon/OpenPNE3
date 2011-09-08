<?php

class File extends ActiveRecord\Model
{
  static $table_name = 'file';

  static $has_one = array(
    'file_bin',
  );

  public function __toString()
  {
    return (string)$this->name;
  }

  public function isImage()
  {
    $type = $this->type;
    if ($type === 'image/jpeg'
      || $type === 'image/gif'
      || $type === 'image/png')
    {
      return true;
    }

    return false;
  }

  public function getImageFormat()
  {
    if (!$this->isImage())
    {
      return false;
    }

    $type = explode('/', $this->type);
    $result = $type[1];

    if ($result === 'jpeg')
    {
      $result = 'jpg';
    }

    return $result;
  }
}
