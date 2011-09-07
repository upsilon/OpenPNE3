<?php

class Profile extends ActiveRecord\Model
{
  const PUBLIC_FLAG_SNS = 1;
  const PUBLIC_FLAG_FRIEND = 2;
  const PUBLIC_FLAG_PRIVATE = 3;
  const PUBLIC_FLAG_WEB = 4;

  static $table_name = 'profile';

  static $has_many = array(
    array(
      'translation',
      'foreign_key' => 'id', 'class' => 'ProfileTranslation', 'index_by' => 'lang',
    ),
  );

 /**
  * Checks if the profile is preset.
  *
  * @return boolean
  */
  public function isPreset()
  {
    return (0 === strpos($this->name, 'op_preset_'));
  }

 /**
  * get preset config
  *
  * @return array
  */
  public function getPresetConfig()
  {
    $list = opToolkit::getPresetProfileList();
    if (!empty($list[$this->getRawPresetName()]))
    {
      return $list[$this->getRawPresetName()];
    }

    return array();
  }

 /**
  * get raw preset name
  *
  * @return string
  */
  public function getRawPresetName()
  {
    if (!$this->isPreset())
    {
      return false;
    }

    $name = substr($this->name, strlen('op_preset_'));

    if ('region_select' === $this->form_type
        && 'string' !== $this->value_type)
    {
      $name .= '_'.$this->value_type;
    }

    return $name;
  }

 /**
  * get options array
  *
  * @return array
  */
  public function getOptionsArray()
  {
    if ($this->isPreset())
    {
      return $this->getPresetOptionsArray();
    }

    $result = array();

    $options = $this->getProfileOption();

    foreach ($options as $option)
    {
      $result[$option->id] = $option->value;
    }

    return $result;
  }

 /**
  * get present options array
  *
  * @return array
  */
  public function getPresetOptionsArray()
  {
    $result = array();
    $config = $this->getPresetConfig();

    if (!empty($config['Choices']))
    {
      $result = array_combine($config['Choices'], $config['Choices']);
    }

    return $result;
  }

 /**
  * get profile options
  *
  * @return Doctrine_Collection
  */
  public function getProfileOption()
  {
    return ProfileOption::find_all_by_profile_id($this->id, array('order', 'sort_order'));
  }

  static public function getProfileId($profileName)
  {
    static $ids = array();
    if (!isset($ids[$profileName]))
    {
      $ids[$profileName] = self::find_by_name($profileName)->id;
    }

    return $ids[$profileName];
  }
}
