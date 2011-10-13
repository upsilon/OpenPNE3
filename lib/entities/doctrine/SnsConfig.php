<?php

/**
 * @Entity(repositoryClass="SnsConfigRepository")
 * @Table(name="sns_config")
 */
class SnsConfig extends sfDoctrineActiveEntity
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
   * @var string $value
   *
   * @Column(type="string", nullable=true)
   */
  private $value;
}
