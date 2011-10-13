<?php

/**
 * @Entity
 * @Table(name="community_member_position")
 */
class CommunityMemberPosition
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
   * @var integer $community_id
   *
   * @Column(type="integer", length=4)
   */
  private $community_id;

  /**
   * @var integer $member_id
   *
   * @Column(type="integer", length=4)
   */
  private $member_id;

  /**
   * @var integer $community_member_id
   *
   * @Column(type="integer", length=4)
   */
  private $community_member_id;

  /**
   * @var string $name
   *
   * @Column(type="string", length=32)
   */
  private $name;

  /**
   * @ManyToOne(targetEntity="Community", cascade={"persist", "remove"})
   */
  private $community;

  /**
   * @ManyToOne(targetEntity="Member", cascade={"persist", "remove"})
   */
  private $member;

  /**
   * @ManyToOne(targetEntity="CommunityMember", cascade={"persist", "remove"})
   */
  private $community_member;
}
