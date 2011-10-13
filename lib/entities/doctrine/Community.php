<?php

/**
 * @Entity(repositoryClass="CommunityRepository")
 * @Table(name="community")
 */
class Community
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
   * @var integer $file_id
   *
   * @Column(type="integer", length=4, nullable=true)
   */
  private $file_id;

  /**
   * @var integer $community_category_id
   *
   * @Column(type="integer", length=4, nullable=true)
   */
  private $community_category_id;

  /**
   * @ManyToOne(targetEntity="File", cascade={"persist", "remove"})
   */
  private $file;
}
