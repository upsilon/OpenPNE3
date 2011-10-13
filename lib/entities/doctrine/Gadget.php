<?php

/**
 * @Entity(repositoryClass="GadgetRepository")
 * @Table(name="gadget")
 */
class Gadget
{
  /**
   * @var integer $id
   *
   * @Column(type="integer", length=4)
   * @Id
   * @GeneratedValue(strategy="IDENTITY")
   */
  private $id;

  /**
   * @var string $type
   *
   * @Column(type="string", length=64)
   */
  private $type;

  /**
   * @var string $name
   *
   * @Column(type="string", length=64)
   */
  private $name;

  /**
   * @var integer $sort_order
   *
   * @Column(type="integer", length=4)
   */
  private $sort_order;

  /**
   * @OneToMany(targetEntity="GadgetConfig", mappedBy="gadget", cascade={"persist", "remove"}, indexBy="name")
   */
  private $configs;

  protected $list = null;


  public function __construct()
  {
    $this->configs = new \Doctrine\Common\Collections\ArrayCollection;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getType()
  {
    return $this->type;
  }

  public function getName()
  {
    return $this->name;
  }

  protected function getGadgetConfigList()
  {
    if (null === $this->list)
    {
      $this->list = sfContext::getInstance()->getEntityManager()->getRepository('Gadget')->getGadgetConfigListByType($this->type);
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
return true;
    $isEnabled = $this->isAllowed($member, 'view');

    return $isEnabled;
  }

  public function getConfig($name)
  {
    $result = null;
    $list = $this->getGadgetConfigList();

    if (isset($this->configs[$name]))
    {
      $result = $this->configs[$name]->getValue();
    }
    elseif (isset($list[$this->getName()]['config'][$name]['Default']))
    {
      $result = $list[$this->getName()]['config'][$name]['Default'];
    }

    return $result;
  }
}
