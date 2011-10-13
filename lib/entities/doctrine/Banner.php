<?php

/**
 * @Entity
 * @Table(name="banner")
 */
class Banner
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
   * @var string $name
   *
   * @Column(type="string", length=64)
   */
  private $name;

  /**
   * @var boolean $is_use_html
   *
   * @Column(type="boolean")
   */
  private $is_use_html;


  public function getIsUseHtml()
  {
    return $this->is_use_html;
  }

  public function getRandomImage()
  {
return false;
  }
}
