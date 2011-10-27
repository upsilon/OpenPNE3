<?php

/**
 * @Entity(repositoryClass="MemberProfileRepository")
 * @Table(name="member_profile")
 */
class MemberProfile extends opDoctrineEntity implements opAccessControlEntityInterface
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
   * @var integer $profile_id
   *
   * @Column(type="integer", length=4)
   */
  private $profile_id;

  /**
   * @var integer $profile_option_id
   *
   * @Column(type="integer", length=4)
   */
  private $profile_option_id;

  /**
   * @var string $value
   *
   * @Column(type="string", length=64)
   */
  private $value;

  /**
   * @var string $value_datetime
   *
   * @Column(type="datetime", nullable=true)
   */
  private $value_datetime;

  /**
   * @var integer $public_flag
   *
   * @Column(type="integer", length=1)
   */
  private $public_flag;

  /**
   * @ManyToOne(targetEntity="Member", inversedBy="profiles")
   */
  private $member;


  public function getId()
  {
    return $this->id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function setName($name)
  {
    $this->name = $name;
  }

  public function getValue()
  {
    return $this->value;
  }

  public function setValue($value, $isDateTime = false)
  {
    $this->value = $value;

    if ($isDateTime)
    {
      $this->setValueDatetime($value);
    }
  }

  public function getValueDatetime()
  {
    return $this->value_datetime;
  }

  public function setValueDatetime($value_datetime)
  {
    $this->value_datetime = $value_datetime;
  }

  public function getPublicFlag()
  {
    return $this->public_flag;
  }

  public function getMember()
  {
    return $this->member;
  }

  public function setMember(Member $member)
  {
    $this->member = $member;
  }

  public function __toString()
  {
    return (string)$this->value;
  }

  public function isViewable($memberId = null)
  {
    if (is_null($memberId))
    {
      $memberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    switch ($this->getPublicFlag())
    {
      case Profile::PUBLIC_FLAG_FRIEND:
        $relation = $this->getEntityManager()->getRepository('MemberRelationship')->retrieveByFromAndTo($this->getMemberId(), $memberId);
        if  ($relation && $relation->isFriend())
        {
          return true;
        }

        return ($this->getMemberId() == $memberId);

      case Profile::PUBLIC_FLAG_PRIVATE:
        return false;

      case Profile::PUBLIC_FLAG_SNS:
        return (bool)$memberId;

      case Profile::PUBLIC_FLAG_WEB:
        return ($this->Profile->is_public_web) ? true : (bool)$memberId;
    }
  }

  public function generateRoleId(Member $member)
  {
    $relation = $this->getEntityManager()->getRepository('MemberRelationship')
      ->findOneBy(array('member_id_from' => $this->getMember()->getId(), 'member_id_to' => $member->getId()));

    if ($this->getMember()->getId() === $member->getId())
    {
      return 'self';
    }
    elseif ($relation)
    {
      if ($relation->getIsAccessBlock())
      {
        return 'blocked';
      }
      elseif ($relation->getIsFriend())
      {
        return 'friend';
      }
    }

    return 'everyone';
  }
}
