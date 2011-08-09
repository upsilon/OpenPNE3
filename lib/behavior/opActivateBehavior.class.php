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
      $columnName = $this->getParameter('name');
      $table = $this->getTable();

      $table->addColumn(array(
        'name' => $columnName,
        'type' => 'boolean',
        'default' => $this->getParameter('default'),
        'notnull' => true,
      ));

      $index = new Index($columnName.'_INDEX');
      $index->addColumn($table->getColumn($columnName));
      $table->addIndex($index);
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
  \$criteria->add({$this->getColumnForParameter('name')->getConstantName()}, false, Criteria::NOT_EQUAL);
}
EOT;
  }
}
