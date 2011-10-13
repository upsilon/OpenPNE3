<?php

use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Entity
 * @Table(name="member_image")
 */
class MemberImage extends \sfDoctrineActiveEntity
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
   * @var integer $member_id
   *
   * @Column(type="integer", length=4)
   */
  private $member_id;

  /**
   * @var integer $file_id
   *
   * @Column(type="integer", length=4)
   */
  private $file_id;

  /**
   * @var boolean $is_primary
   *
   * @Column(type="boolean")
   */
  private $is_primary;

  /**
   * @var datetime $created_at
   *
   * @Column(type="datetime")
   * @Gedmo\Timestampable(on="create")
   */
  private $created_at;

  /**
   * @var datetime $updated_at
   *
   * @Column(type="datetime")
   * @Gedmo\Timestampable(on="update")
   */
  private $updated_at;

  /**
   * @ManyToOne(targetEntity="Member", inversedBy="images")
   */
  private $member;

  /**
   * @OneToOne(targetEntity="File", cascade={"persist", "remove"})
   * @JoinColumn(name="file_id", referencedColumnName="id")
   */
  private $file;


  public function getFile()
  {
    return $this->file;
  }
}
