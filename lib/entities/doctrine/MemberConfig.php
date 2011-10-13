<?php

/**
 * @Entity
 * @Table(name="member_config")
 */
class MemberConfig
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
   * @var string $name
   *
   * @Column(type="string", length=64)
   */
  private $name;

  /**
   * @var string $value
   *
   * @Column(type="string")
   */
  private $value;

  /**
   * @var string $value_datetime
   *
   * @Column(type="datetime", nullable=true)
   */
  private $value_datetime;

  /**
   * @ManyToOne(targetEntity="Member", inversedBy="configs")
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

  public function getMember()
  {
    return $this->member;
  }

  public function setMember(Member $member)
  {
    $this->member = $member;
  }
}
