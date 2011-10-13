<?php

/**
 * @Entity(repositoryClass="GadgetConfigRepository")
 * @Table(name="gadget_config")
 */
class GadgetConfig
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
   * @var integer $gadget_id
   *
   * @Column(type="integer", length=4)
   */
  private $gadget_id;

  /**
   * @var string $value
   *
   * @Column(type="string", nullable=true)
   */
  private $value;

  /**
   * @ManyToOne(targetEntity="Gadget", inversedBy="configs")
   */
  private $gadget;

}
