<?php

class Gadget extends ActiveRecord\Model
{
  static $table_name = 'gadget';

  static protected
    $results,
    $configs = array(),
    $gadgets = array(),
    $gadgetConfigList = array();

  protected $list = null;

  protected function getGadgetConfigList()
  {
    if (null === $this->list)
    {
      $this->list = self::getGadgetConfigListByType($this->type);
    }
    return $this->list;
  }

  public function getComponentModule()
  {
    $list = $this->getGadgetConfigList();
    if (empty($list[$this->name]))
    {
      return false;
    }

    return $list[$this->name]['component'][0];
  }

  public function getComponentAction()
  {
    $list = $this->getGadgetConfigList();
    if (empty($list[$this->name]))
    {
      return false;
    }

    return $list[$this->name]['component'][1];
  }

  public function isEnabled()
  {
    $list = $this->getGadgetConfigList();
    if (empty($list[$this->name]))
    {
      return false;
    }

    $controller = sfContext::getInstance()->getController();
    if (!$controller->componentExists($this->getComponentModule(), $this->getComponentAction()))
    {
      return false;
    }

    $member = sfContext::getInstance()->getUser()->getMember();
    if (!$member)
    {
      $member = sfContext::getInstance()->getUser()->getMember(true);
    }

//    if (!$member || !$this->isAllowed($member, 'view'))
//    {
//      return false;
//    }

    return true;
  }

  public function getConfig($name)
  {
    $result = null;
    $list = $this->getGadgetConfigList();

    $config = GadgetConfig::find_by_gadget_id_and_name($this->id, $name);
    if ($config)
    {
      $result = $config->value;
    }
    elseif (isset($list[$this->name]['config'][$name]['Default']))
    {
      $result = $list[$this->name]['config'][$name]['Default'];
    }

    return $result;
  }

  static public function getConfigs()
  {
    if (!isset(self::$configs['config']))
    {
      self::$configs['config'] = include(sfContext::getInstance()
        ->getConfiguration()
        ->getConfigCache()
        ->checkConfig('config/gadget_config.yml'));
    }
    return self::$configs['config'];
  }

  static public function getGadgetLayoutConfig()
  {
    if (!isset(self::$configs['layout']))
    {
      self::$configs['layout'] = include(sfContext::getInstance()
        ->getConfiguration()
        ->getConfigCache()
        ->checkConfig('config/gadget_layout_config.yml'));
    }
    return self::$configs['layout'];
  }

  static public function getGadgetConfig($typesName)
  {
    if (!isset(self::$configs['gadget'][$typesName]))
    {
      $filename = 'config/'.sfinflector::underscore($typesName);
      if ($typesName != 'gadget')
      {
        $filename .= '_gadget';
      }
      $filename .= '.yml';

      $configCache = sfContext::getInstance()->getConfiguration()->getConfigCache();
      $configCache->registerConfigHandler($filename, 'opGadgetConfigHandler');
      self::$configs['gadget'][$typesName] = include($configCache->checkConfig($filename));
    }
    return self::$configs['gadget'][$typesName];
  }

  static protected function getTypes($typesName)
  {
    $types = array();
    $configs = self::getConfigs();
    $layoutConfigs = self::getGadgetLayoutConfig();

    if (!isset($configs[$typesName]))
    {
      throw new Doctrine_Exception('Invalid types name');
    }
    if (isset($configs[$typesName]['layout']['choices']))
    {
      foreach ($configs[$typesName]['layout']['choices'] as $choice)
      {
        $types = array_merge($types, $layoutConfigs[$choice]);
      }
    }
    $types = array_merge($types, $layoutConfigs[$configs[$typesName]['layout']['default']]);
    $types = array_unique($types);

    if ($typesName !== 'gadget')
    {
      foreach ($types as &$type)
      {
        $type = $typesName.ucfirst($type);
      }
    }

    return $types;
  }

  static public function clearGadgetsCache()
  {
    $files = sfFinder::type('file')
      ->name('*_gadgets.php')
      ->in(sfConfig::get('sf_root_dir').'/cache');
    foreach ($files as $file)
    {
      @unlink($file);
    }
    self::$gadgets = array();
    self::$gadgetConfigList = array();
  }

  static public function retrieveGadgetsByTypesName($typesName)
  {
    if (isset(self::$gadgets[$typesName]))
    {
      return self::$gadgets[$typesName];
    }

    if (sfConfig::get('op_is_enable_gadget_cache', true))
    {
      $dir = sfConfig::get('sf_app_cache_dir').'/config';
      $file = $dir.'/'.sfInflector::underscore($typesName)."_gadgets.php";
      if (is_readable($file))
      {
        $results = unserialize(file_get_contents($file));
        self::$gadgets[$typesName] = $results;
        return $results;
      }
    }

    $types = self::getTypes($typesName);

    foreach($types as $type)
    {
      $results[$type] = self::retrieveByType($type);
    }

    if (sfConfig::get('op_is_enable_gadget_cache', true))
    {
      if (!is_dir($dir))
      {
        @mkdir($dir, 0777, true);
      }
      file_put_contents($file, serialize($results));
    }

    self::$gadgets[$typesName] = $results;

    return $results;
  }

  static public function retrieveByType($type)
  {
    $results = self::find_all_by_type($type);

    return (0 === count($results)) ? null : $results;
  }

  static public function getGadgetsIds($type)
  {
    return self::find_all_by_type($type, array('select' => 'id'));
  }

  static protected function getResults()
  {
    if (empty(self::$results))
    {
      self::$results = array();
      $objects = self::all();
      foreach ($objects as $object)
      {
        self::$results[$object->type][] = $object;
      }
    }
    return self::$results;
  }

  static public function getGadgetConfigListByType($type)
  {
    if (isset(self::$gadgetConfigList[$type]))
    {
      return self::$gadgetConfigList[$type];
    }

    $configs = self::getConfigs();
    foreach ($configs as $key => $config)
    {
      if (in_array($type, self::getTypes($key)))
      {
        $resultConfig = self::getGadgetConfig($key);
        self::$gadgetConfigList[$type] = $resultConfig;
        return $resultConfig;
      }
    }

    self::$gadgetConfigList[$type] = array();
    return array();
  }
}
