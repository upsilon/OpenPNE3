<?php

require_once dirname(__FILE__).'/../../plugins/sfPropel15Plugin/lib/vendor/propel-generator/lib/model/Behavior.php';

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opActivateBehavior extends Behavior
{
  protected $parameters = array(
    'name'    => 'is_active',
    'default' => false,
  );

  protected static $enabled = true;

  public function modifyTable()
  {
    if (!$this->getTable()->containsColumn($this->getParameter('name')))
    {
      $this->getTable()->addColumn(array(
        'name' => $this->getParameter('name'),
        'type' => 'boolean',
        'default' => $this->_options['default'],
        'notnull' => true,
      ));

      /*
      $this->index($this->_options['name'].'_INDEX', array(
        'fields' => array($this->_options['name']),
      ));
      */
    }
  }

  public static function enable()
  {
    self::$enabled = true;
  }

  public static function disable()
  {
    self::$enabled = false;
  }

  public static function getEnabled()
  {
    return self::$enabled;
  }

  public function preSelect($builder)
  {
    return <<<EOT
if (opActivateBehavior::getEnabled()) {
  \$c1 = \$criteria->getNewCriterion({$this->getColumnForParameter('name')->getConstantName()}, true);
  \$c1->addOr(\$criteria->getNewCriterion({$this->getColumnForParameter('name')->getConstantName()}, null, Criteria::ISNULL));
  \$criteria->add(\$c1);
}
EOT;
  }
}
