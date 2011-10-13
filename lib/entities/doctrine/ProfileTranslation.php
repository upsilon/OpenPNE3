<?php

/**
 * @Entity
 * @Table(name="profile_translation")
 */
class ProfileTranslation
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
   * @var string $info
   *
   * @Column(type="string", nullable=true)
   */
  private $info;

  /**
   * @ManyToOne(targetEntity="Profile", inversedBy="translations")
   * @JoinColumn(name="id", referencedColumnName="id")
   */
  private $profile;
}
