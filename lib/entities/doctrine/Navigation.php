<?php

/**
 * @Entity(repositoryClass="NavigationRepository")
 * @Table(name="navigation")
 */
class Navigation extends AbstractTranslatable
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
   * @Column(type="string", length=64, nullable=true)
   */
  private $type;

  /**
   * @var integer $uri
   *
   * @Column(type="string")
   */
  private $uri;

  /**
   * @var integer sort_order
   *
   * @Column(type="integer", length=4, nullable=true)
   */
  private $sort_order;

  /**
   * @OneToMany(targetEntity="NavigationTranslation", mappedBy="navigation", cascade={"persist", "remove"}, indexBy="lang")
   */
  protected $translations;


  public function getId()
  {
    return $this->id;
  }

  public function getType()
  {
    return $this->type;
  }

  public function getUri()
  {
    return $this->uri;
  }

  public function getSortOrder()
  {
    return $this->sort_order;
  }

  public function getCaption()
  {
    return $this->getTranslateColumn('caption');
  }
}
