<?php


class MemberAcl
{
  static public function appendRoles(Zend_Acl $acl)
  {
    return $acl
      ->addRole(new Zend_Acl_Role('anonymous'))
      ->addRole(new Zend_Acl_Role('everyone'), 'anonymous')
      ->addRole(new Zend_Acl_Role('self'), 'everyone')
      ->addRole(new Zend_Acl_Role('blocked'));
  }

  static public function appendRules(Zend_Acl $acl, $resource = null)
  {
    $acl->allow('everyone', $resource, 'view')
      ->allow('self', $resource, 'edit')
      ->deny('blocked');

    if (SnsConfigPeer::get('is_allow_config_public_flag_profile_page'))
    {
      $config = SnsConfigPeer::get('is_allow_config_public_flag_profile_page');
    }
    elseif ($resource)
    {
      $config = $resource->getConfig('profile_page_public_flag');
    }

    if ($config && 4 == $config)
    {
      $acl->allow('anonymous', $resource, 'view');
    }

    return $acl;
  }

  static public function generateRoleId(Member $record, Member $viewer)
  {
    $relation = MemberRelationshipQuery::create()->findOneByMemberIdFromAndMemberIdTo($record->getId(), $viewer->getId());

    if ($record->getId() === $viewer->getId())
    {
      return 'self';
    }
    elseif ($relation && $relation->getIsAccessBlock())
    {
      return 'blocked';
    }
    elseif ($viewer instanceof opAnonymousMember)
    {
      return 'anonymous';
    }

    return 'everyone';
  }
}
