<?php


class MemberProfileAcl
{
  static public function appendRoles(Zend_Acl $acl)
  {
    return $acl
      ->addRole(new Zend_Acl_Role('everyone'))
      ->addRole(new Zend_Acl_Role('friend'), 'everyone')
      ->addRole(new Zend_Acl_Role('self'), 'friend')
      ->addRole(new Zend_Acl_Role('blocked'));
  }

  static public function appendRules(Zend_Acl $acl, $resource = null)
  {
    $assertion = new opMemberProfilePublicFlagAssertion();

    return $acl
      ->allow('everyone', $resource, 'view', $assertion)
      ->allow('friend', $resource, 'view', $assertion)
      ->allow('self', $resource, 'view', $assertion)
      ->allow('self', $resource, 'edit')
      ->deny('blocked');
  }

  static public function generateRoleId(MemberProfile $record, Member $viewer)
  {
    $relation = MemberRelationshipQuery::create()->findOneByFromAndTo($record->getMemberId(), $viewer->getId());

    if ($record->getMemberId() === $viewer->getId())
    {
      return 'self';
    }
    elseif ($relation)
    {
      if ($relation->getIsAccessBlock())
      {
        return 'blocked';
      }
      elseif ($relation->getIsFriend())
      {
        return 'friend';
      }
    }

    return 'everyone';
  }
}

class opMemberProfilePublicFlagAssertion implements Zend_Acl_Assert_Interface
{
  public function assert(Zend_Acl $acl, Zend_Acl_Role_Interface $role = null, Zend_Acl_Resource_Interface $resource = null, $privilege = null)
  {
    if (ProfilePeer::PUBLIC_FLAG_FRIEND == $resource->getPublicFlag())
    {
      return ($role->getRoleId() === 'self' || $role->getRoleId() === 'friend');
    }

    if (ProfilePeer::PUBLIC_FLAG_PRIVATE == $resource->getPublicFlag())
    {
      return ($role->getRoleId() === 'self');
    }

    return true;
  }
}
