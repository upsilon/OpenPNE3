<?php

class opPropelRecord extends BaseObject implements Zend_Acl_Resource_Interface
{
  public function getResourceId()
  {
    $tableName = constant(static::PEER.'::TABLE_NAME');
    $identifier = array_values((array)$this->getPrimaryKey());
    $identifier = array_shift($identifier);

    return $tableName.'.'.$identifier;
  }
}
