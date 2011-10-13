<?php

/**
 * @Entity(repositoryClass="CommunityMemberRepository")
 * @Table(name="community_member")
 */
class CommunityMember
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
   * @var boolean $is_pre
   *
   * @Column(type="boolean")
   */
  private $is_pre = false;

  /**
   * @var boolean $is_receive_mail_pc
   *
   * @Column(type="boolean")
   */
  private $is_receive_mail_pc = false;

  /**
   * @var booelan $is_receive_mail_mobile
   *
   * @Column(type="boolean")
   */
  private $is_receive_mail_mobile = false;

  /**
   * @ManyToOne(targetEntity="Community", cascade={"persist", "remove"})
   */
  private $community;

  /**
   * @ManyToOne(targetEntity="Member", cascade={"persist", "remove"})
   */
  private $member;
}
