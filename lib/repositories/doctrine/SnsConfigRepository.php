<?php

class SnsConfigRepository extends \Doctrine\ORM\EntityRepository
{
  protected $configs;

  public function retrieveByName($name)
  {
    $configs = $this->getConfigs();
    return (isset($configs[$name])) ? $configs[$name] : null;
  }

  public function get($name, $default = null)
  {
    return (!is_null($config = $this->retrieveByName($name))) ? $config->getValue() : $default;
  }

  public function set($name, $value)
  {
    $config = $this->retrieveByName($name);
    if (!$config)
    {
      $config = new SnsConfig();
      $config->setName($name);
    }
    $config->setValue($value);

    $this->configs[$name] = $config;

    $this->em->persist($config);
  }

  protected function getConfigs()
  {
    if (is_null($this->configs))
    {
      $this->configs = array();

      foreach ($this->findAll() as $config)
      {
        $this->configs[$config->name] = $config;
      }
    }

    return $this->configs;
  }
}
