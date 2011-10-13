<?php

/**
 * @Entity
 * @Table(name="sns_term_translation")
 */
class SnsTermTranslation
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
   * @var string $value
   *
   * @Column(type="string")
   */
  private $value;

  /**
   * @ManyToOne(targetEntity="SnsTerm", inversedBy="translations")
   * @JoinColumn(name="id", referencedColumnName="id")
   */
  private $sns_term;


  public function getLang()
  {
    return $this->lang;
  }

  public function getValue()
  {
    return $this->value;
  }
}
