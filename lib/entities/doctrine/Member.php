<?php

/**
 * @Entity(repositoryClass="MemberRepository")
 * @Table(name="member")
 */
class Member
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
   * @var integer $invite_member_id
   *
   * @Column(type="integer", length=4, nullable=true)
   */
  private $invite_member_id;

  /**
   * @var boolean $is_login_rejected
   *
   * @Column(type="boolean")
   */
  private $is_login_rejected;

  /**
   * @var boolean $is_active
   *
   * @Column(type="boolean")
   */
  private $is_active;

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

  /**
   * @OneToMany(targetEntity="MemberConfig", mappedBy="member", cascade={"persist", "remove"}, indexBy="name")
   */
  private $configs;

  /**
   * @OneToMany(targetEntity="MemberProfile", mappedBy="member", cascade={"persist", "remove"}, indexBy="id")
   */
  private $profiles;

  /**
   * @OneToMany(targetEntity="MemberImage", mappedBy="member", cascade={"persist", "remove"})
   */
  private $images;

  public function __construct()
  {
    $this->configs = new \Doctrine\Common\Collections\ArrayCollection;
    $this->profiles = new \Doctrine\Common\Collections\ArrayCollection;
    $this->images = new \Doctrine\Common\Collections\ArrayCollection;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getIsLoginRejected()
  {
    return $this->is_login_rejected;
  }

  public function getIsActive()
  {
    return $this->is_active;
  }

  public function countFriendPreTo(\Doctrine\ORM\QueryBuilder $q = null)
  {
    if (!$q)
    {
      $q = sfContext::getInstance()->getEntityManager()->getRepository('MemberRelationship')->createQueryBuilder('mr');
    }

    $q
      ->select('COUNT(mr)')
      ->where($q->expr()->eq('mr.member_id_to', $this->getId()))
      ->where('mr.is_friend_pre = true');

    return $q->getQuery()->getSingleScalarResult();
  }

  public function countFriendPreFrom(\Doctrine\ORM\QueryBuilder $q = null)
  {
    if (!$q)
    {
      $q = sfContext::getInstance()->getEntityManager()->getRepository('MemberRelationship')->createQueryBuilder();
    }

    $q
      ->select('COUNT(*)')
      ->where($q->expr()->eq('member_id_from', $this->getId()))
      ->where('is_friend_pre = true');

    return $q->getQuery()->getSingleScalaResult();
  }

  public function getImage()
  {
    return count($this->images) >= 1 ? $this->images->first() : null;
  }

  public function getImageFileName()
  {
    $image = $this->getImage();
    if ($image)
    {
      return $image->getFile();
    }

    return false;
  }

  public function getProfile($profileName, $viewableCheck = false, $myMemberId = null)
  {
    $profileId = sfContext::getInstance()->getEntityManager()->getRepository('Profile')->getIdByName($profileName);
    return $this->profiles[$profileId];

    $profile = Doctrine::getTable('MemberProfile')->retrieveByMemberIdAndProfileName($this->getId(), $profileName);
    if (!$profile)
    {
      return null;
    }

    if ($viewableCheck)
    {
      if ($myMemberId)
      {
        $member = Doctrine::getTable('Member')->find($myMemberId);
      }
      else
      {
        $member = sfContext::getInstance()->getUser()->getMember();
      }

      if (!$member)
      {
        return null;
      }

      if (!$profile->isAllowed($member, 'view'))
      {
        return null;
      }
    }

    return $profile;
  }

  public function getConfig($configName, $default = null)
  {
    return isset($this->configs[$configName]) ? $this->configs[$configName]->getValue() : $default;
  }

  public function setConfig($configName, $value, $isDateTime = false)
  {
    if (isset($this->configs[$configName]))
    {
      $config = $this->configs[$configName];
    }
    else
    {
      $config = new MemberConfig;
      $config->setName($configName);
      $config->setMember($this);
      $this->configs->add($config);
    }

    $config->setValue($value, $isDateTime);
    sfContext::getInstance()->getEntityManager()->persist($config);
  }

  public function getFriends($limit = null, $isRandom = false)
  {
    return sfContext::getInstance()->getEntityManager()->getRepository('MemberRelationship')->getFriends($this->getId(), $limit, $isRandom);
  }

  public function countFriends()
  {
    return count(sfContext::getInstance()->getEntityManager()->getRepository('MemberRelationship')->getFriendMemberIds($this->getId()));
  }

  public function getNameAndCount($format = '%s (%d)')
  {
    if (!opConfig::get('enable_friend_link'))
    {
      return $this->getName();
    }
    return sprintf($format, $this->getName(), $this->countFriends());
  }

  public function updateLastLoginTime()
  {
    $this->setConfig('lastLogin', date('Y-m-d H:i:s'), true);
  }

  public function getLastLoginTime()
  {
    return strtotime($this->getConfig('lastLogin'));
  }

  public function isOnBlackList()
  {
    $uid = $this->getConfig('mobile_uid');
    if ($uid)
    {
      return (bool)Doctrine::getTable('Blacklist')->retrieveByUid($uid);
    }

    return false;
  }

  public function countJoinCommunity()
  {
    static $cache = null;

    if (is_null($cache))
    {
      $qb = sfContext::getInstance()->getEntityManager()->getRepository('CommunityMember')->createQueryBuilder('cm');
      $cache = $qb
        ->select('COUNT(cm)')
        ->where($qb->expr()->eq('cm.member_id', $this->getId()))
        ->where('cm.is_pre = false')
        ->getQuery()
        ->getSingleScalarResult();
    }

    return $cache;
  }
}
