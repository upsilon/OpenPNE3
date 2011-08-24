<?php

require_once dirname(__FILE__).'/../../plugins/sfPropelORMPlugin/lib/vendor/propel/generator/lib/model/Behavior.php';

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opSortOrderBehavior extends Behavior
{
  protected $parameters = array(
    'name'    => 'sort_order',
  );

  static protected
    $uniqueNameCount = 1;

  public function modifyTable()
  {
    if (!$this->getTable()->containsColumn($this->getParameter('name')))
    {
      $columnName = $this->getParameter('name');
      $table = $this->getTable();

      $table->addColumn(array(
        'name' => $columnName,
        'type' => 'integer',
        'required' => 'true',
      ));

      $index = new Index($columnName.'_INDEX'.self::$uniqueNameCount++);
      $index->addColumn($table->getColumn($columnName));
      $table->addIndex($index);
    }
  }

  public function preSelectQuery($builder)
  {
    return <<<EOT
\$this->addAscendingOrderByColumn({$this->getColumnForParameter('name')->getConstantName()});
EOT;
  }

  public function preSelect($builder)
  {
    return <<<EOT
\$criteria->addAscendingOrderByColumn({$this->getColumnForParameter('name')->getConstantName()});
EOT;
  }
}
