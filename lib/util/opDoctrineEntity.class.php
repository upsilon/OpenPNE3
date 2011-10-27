<?php

abstract class opDoctrineEntity implements Zend_Acl_Resource_Interface
{
  protected
    $roleList = array();

  protected function getEntityManager()
  {
    return sfContext::getInstance()->getEntityManager();
  }

  public function getRepository()
  {
    return $this->getEntityManager()->getRepository(get_class($this));
  }

  public function getResourceId()
  {
    $tableName = get_class($this);
    $identifier = array_values(array($this->getId()));
    $identifier = array_shift($identifier);

    return $tableName.'.'.$identifier;
  }

  public function getRoleId(Member $member)
  {
    $this->checkReadyForAcl();

    if (empty($this->roleList[$member->getId()]))
    {
      $this->roleList[$member->getId()] = $this->generateRoleId($member);
    }

    return $this->roleList[$member->getId()];
  }

  public function clearRoleList()
  {
    $this->checkReadyForAcl();

    $this->roleList = array();
  }

  public function isAllowed(Member $member, $privilege)
  {
    $this->checkReadyForAcl();

    $acl = $this->getRepository()->getAcl($this);

    return $acl->isAllowed($this->getRoleId($member), $this, $privilege);
  }

  public function checkReadyForAcl()
  {
    if (!($this instanceof opAccessControlEntityInterface))
    {
      throw new LogicException(sprintf('%s must implement the opAccessControlEntityInterface for access controll.', get_class($this)));
    }

    if (!($this->getRepository() instanceof opAccessControlDoctrineRepository))
    {
      throw new LogicException(sprintf('%s must be subclass of the opAccessControlDoctrineRepository for access controll.', get_class($this->getRepository())));
    }
  }
}
