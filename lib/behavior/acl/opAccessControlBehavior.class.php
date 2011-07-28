<?php

require_once dirname(__FILE__).'/../../../plugins/sfPropel15Plugin/lib/vendor/propel-generator/lib/model/Behavior.php';

class opAccessControlBehavior extends Behavior
{
  protected $additionalBuilders = array('opAccessControlBehaviorBuilder');

  public function objectAttribute($builder)
  {
    return "
protected \$roleList = array();
";
  }

  public function objectMethods($builder)
  {
    $aclClassName = $this->getTable()->getPhpName().'Acl';
    return "
public function getRoleId(Member \$member)
{
  if (empty(\$this->roleList[\$member->getId()]))
  {
    \$this->roleList[\$member->getId()] = $aclClassName::generateRoleId(\$this, \$member);
  }

  return \$this->roleList[\$member->getId()];
}

public function clearRoleList()
{
  \$this->roleList = array();
}

public function isAllowed(Member \$member, \$privilege)
{
  \$acl = self::getAcl(\$this);

  return \$acl->isAllowed(\$this->getRoleId(\$member), \$this, \$privilege);
}

static public function getAcl(\$resource)
{
  static \$acl = null;
  if (!\$acl)
  {
    \$acl = new Zend_Acl();
    \$acl = $aclClassName::appendRoles(\$acl);
  }

  if (\$resource && !\$acl->has(\$resource))
  {
    \$acl->add(\$resource);
    \$acl = $aclClassName::appendRules(\$acl, \$resource);
  }

  return \$acl;
}
";
  }
}
