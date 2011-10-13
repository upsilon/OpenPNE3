<?php

/**
 * @Entity
 * @Table(name="navigation_translation")
 */
class NavigationTranslation
{
  /**
   * @var integer $id
   *
   * @Column(type="integer", length=4)
   * @Id
   */
  private $id;

  /**
   * @var string $lang
   *
   * @Column(type="string", length=5)
   * @Id
   */
  private $lang;

  /**
   * @var string $caption
   *
   * @Column(type="string")
   */
  private $caption;

  /**
   * @ManyToOne(targetEntity="Navigation", inversedBy="translations")
   * @JoinColumn(name="id", referencedColumnName="id")
   */
  private $navigation;


  public function getCaption()
  {
    return $this->caption;
  }
}
