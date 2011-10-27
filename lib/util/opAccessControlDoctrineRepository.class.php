<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAccessControlDoctrineRepository
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class opAccessControlDoctrineRepository extends \Doctrine\ORM\EntityRepository
{
  protected $acl = null;

  public function getAcl($resource)
  {
    if (!$this->acl)
    {
      $this->acl = new Zend_Acl();
      $this->acl = $this->appendRoles($this->acl);
    }

    if ($resource && !$this->acl->has($resource))
    {
      $this->acl->add($resource);
      $this->acl = $this->appendRules($this->acl, $resource);
    }

    return $this->acl;
  }

  abstract public function appendRoles(Zend_Acl $acl);

  abstract public function appendRules(Zend_Acl $acl, $resource = null);
}
