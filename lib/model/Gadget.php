<?php



/**
 * Skeleton subclass for representing a row from the 'gadget' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.6.0-dev on:
 *
 * Wed Jul 13 23:36:50 2011
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model
 */
class Gadget extends BaseGadget {
  protected $list = null;

  public function preSave($event)
  {
    if (!$this->getSortOrder())
    {
      $maxSortOrder = 0;

      $gadgets = Doctrine::getTable('Gadget')->retrieveByType($this->getType());
      if ($gadgets)
      {
        $finalGadget = array_pop($gadgets);
        if ($finalGadget)
        {
          $maxSortOrder = $finalGadget->getSortOrder();
        }
      }

      $this->setSortOrder($maxSortOrder + 10);
    }
  }

  protected function getGadgetConfigList()
  {
    if (null === $this->list)
    {
      $this->list = GadgetPeer::getGadgetConfigListByType($this->getType());
    }
    return $this->list;
  }

  public function getComponentModule()
  {
    $list = $this->getGadgetConfigList();
    if (empty($list[$this->name]))
    {
      return false;
    }

    return $list[$this->name]['component'][0];
  }

  public function getComponentAction()
  {
    $list = $this->getGadgetConfigList();
    if (empty($list[$this->name]))
    {
      return false;
    }

    return $list[$this->name]['component'][1];
  }

  public function isEnabled()
  {
    $list = $this->getGadgetConfigList();
    if (empty($list[$this->name]))
    {
      return false;
    }

    $controller = sfContext::getInstance()->getController();
    if (!$controller->componentExists($this->getComponentModule(), $this->getComponentAction()))
    {
      return false;
    }

    $member = sfContext::getInstance()->getUser()->getMember();
    if (!$member)
    {
      $member = sfContext::getInstance()->getUser()->getMember(true);
    }

    if (!$member || !$this->isAllowed($member, 'view'))
    {
      return false;
    }

    return true;
  }

  public function getConfig($name)
  {
    $result = null;
    $list = $this->getGadgetConfigList();

    $config = GadgetConfigQuery::create()->findOneByGadgetIdAndName($this->getId(), $name);
    if ($config)
    {
      $result = $config->getValue();
    }
    elseif (isset($list[$this->getName()]['config'][$name]['Default']))
    {
      $result = $list[$this->getName()]['config'][$name]['Default'];
    }

    return $result;
  }
} // Gadget