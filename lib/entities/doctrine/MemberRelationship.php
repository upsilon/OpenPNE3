<?php

/**
 * @Entity(repositoryClass="MemberRelationshipRepository")
 * @Table(name="member_relationship")
 */
class MemberRelationship extends opDoctrineEntity
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
   * @var integer $member_id_to
   *
   * @Column(type="integer", length=4)
   */
  private $member_id_to;

  /**
   * @var integer $member_id_from
   *
   * @Column(type="integer", length=4)
   */
  private $member_id_from;

  /**
   * @var boolean $is_friend
   *
   * @Column(type="boolean")
   */
  private $is_friend;

  /**
   * @var boolean $is_friend_pre
   *
   * @Column(type="boolean")
   */
  private $is_friend_pre;

  /**
   * @var boolean $is_access_block
   *
   * @Column(type="boolean")
   */
  private $is_access_block;

  /**
   * @var datetime $created_at
   *
   * @Column(type="datetime")
   */
  private $created_at;

  /**
   * @var datetime $updated_at
   *
   * @Column(type="datetime")
   */
  private $updated_at;


  public function getId()
  {
    return $this->id;
  }

  public function getMemberIdTo()
  {
    return $this->member_id_to;
  }

  public function setMemberIdTo($member_id_to)
  {
    $this->member_id_to = $member_id_to;
  }

  public function getMemberIdFrom()
  {
    return $this->member_id_from;
  }

  public function setMemberIdFrom($member_id_from)
  {
    $this->member_id_from = $member_id_from;
  }

  public function getIsFriend()
  {
    return $this->is_friend;
  }
}
