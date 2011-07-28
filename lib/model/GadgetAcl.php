<?php


class GadgetAcl
{
  static public function appendRoles(Zend_Acl $acl)
  {
    return $acl
      ->addRole(new Zend_Acl_Role('anonymous'))
      ->addRole(new Zend_Acl_Role('everyone'), 'anonymous');
  }

  static public function appendRules(Zend_Acl $acl, $resource = null)
  {
    $acl->allow('everyone', $resource, 'view');

    if (4 == $resource->getConfig('viewable_privilege'))
    {
      $acl->allow('anonymous', $resource, 'view');
    }

    return $acl;
  }

  static public function generateRoleId(Gadget $record, Member $viewer)
  {
    if ($viewer instanceof opAnonymousMember)
    {
      return 'anonymous';
    }

    return 'everyone';
  }
}
