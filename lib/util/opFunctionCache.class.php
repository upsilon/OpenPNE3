<?php

class opFunctionCache extends sfFunctionCache
{
  protected
    $useSerialize = false;

  public function __construct(sfCache $cache = null)
  {
    if (null === $cache)
    {
      $cache = opArrayCache::getInstance();
    }

    parent::__construct($cache);
  }

  public function call($callable, $arguments = array())
  {
    if ($this->useSerialize)
    {
      return parent::call($callable, $arguments);
    }

    // Generate a cache id
    $key = $this->computeCacheKey($callable, $arguments);

    $data = $this->cache->get($key);
    if (null === $data)
    {
      $data = array();

      if (!is_callable($callable))
      {
        throw new sfException('The first argument to call() must be a valid callable.');
      }

      ob_start();
      ob_implicit_flush(false);

      try
      {
        $data['result'] = call_user_func_array($callable, $arguments);
      }
      catch (Exception $e)
      {
        ob_end_clean();
        throw $e;
      }

      $data['output'] = ob_get_clean();

      $this->cache->set($key, $data);
    }

    echo $data['output'];

    return $data['result'];
  }
}
