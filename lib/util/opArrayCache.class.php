<?php

class opArrayCache extends sfCache
{
  protected
    $cache = array();

  static public function getInstance()
  {
    static $instance = null;
    if (null === $instance)
    {
      $instance = new self();
    }

    return $instance;
  }

  public function initialize($options = array())
  {
    parent::initialize($options);
  }

  public function get($key, $default = null)
  {
    if ($this->has($key))
    {
      return $this->cache[$key]['data'];
    }

    return $default;
  }

  public function has($key)
  {
    return isset($this->cache[$key]) && $this->cache[$key]['timeout'] > time();
  }

  public function set($key, $data, $lifetime = null)
  {
    if ($this->getOption('automatic_cleaning_factor') > 0 && rand(1, $this->getOption('automatic_cleaning_factor')) == 1)
    {
      $this->clean(sfCache::OLD);
    }

    $this->cache[$key] = array(
      'data'          => $data,
      'timeout'       => time() + $this->getLifetime($lifetime),
      'last_modified' => time(),
    );

    return true;
  }

  public function remove($key)
  {
    if (isset($this->cache[$key]))
    {
      unset($this->cache[$key]);
    }

    return true;
  }

  public function removePattern($pattern)
  {
    $now = time();
    $regexp = self::patternToRegexp($pattern);

    foreach ($this->cache as $key => $cache)
    {
      if (preg_match($regexp, $key) && $cache['timeout'] > $now)
      {
        unset($this->cache[$key]);
      }
    }

    return true;
  }

  public function clean($mode = sfCache::ALL)
  {
    $now = time();

    if (sfCache::ALL === $mode)
    {
      $this->cache = array();
      return true;
    }
    elseif (sfCache::OLD === $mode)
    {
      foreach ($this->cache as $key => $cache)
      {
        if ($cache['timeout'] < $now)
        {
          unset($this->cache[$key]);
        }
      }
      return true;
    }

    return false;
  }

  public function getTimeout($key)
  {
    if ($this->has($key))
    {
      return $this->cache[$key]['timeout'];
    }

    return 0;
  }

  public function getLastModified($key)
  {
    if ($this->has($key))
    {
      return $this->cache[$key]['last_modified'];
    }

    return 0;
  }

  public function getMany($keys)
  {
    $result = array();

    foreach ($keys as $key)
    {
      $data = $this->get($key);
      if (null !== $data)
      {
        $result[$key] = $data;
      }
    }

    return $result;
  }
}
