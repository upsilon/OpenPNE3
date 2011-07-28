<?php

abstract class opAccessControlBase
{
  static protected $acl = null;

  static public function getAcl($resource)
  {
    if (!self::$acl)
    {
      self::$acl = new Zend_Acl();
      self::$acl = self::appendRoles(self::$acl);
    }

    if ($resource && !self::$acl->has($resource))
    {
      self::$acl->add($resource);
      self::$acl = self::appendRules(self::$acl, $resource);
    }

    return self::$acl;
  }
}
