<?php

require_once dirname(__FILE__).'/../../plugins/sfPropel15Plugin/lib/vendor/propel-generator/lib/builder/om/OMBuilder.php';

class opAccessControlBehaviorBuilder extends OMBuilder
{
  public $overwrite = false;

  public function getUnprefixedClassname()
  {
    return $this->getStubObjectBuilder()->getUnprefixedClassname().'Acl';
  }

  protected function addClassOpen(&$script)
  {
    $script .= "
class {$this->getClassname()}
{";
  }

  protected function addClassBody(&$script)
  {
    $tableClassName = $this->getStubObjectBuilder()->getFullyQualifiedClassname();
    $script .= "
  static public function appendRoles(Zend_Acl \$acl)
  {
    
  }

  static public function appendRules(Zend_Acl \$acl, \$resource = null)
  {
    
  }

  static public function generateRoleId({$tableClassName} \$record, Member \$viewer)
  {
    
  }";
  }

  protected function addClassClose(&$script)
  {
    $script .= "
}
";
  }
}
