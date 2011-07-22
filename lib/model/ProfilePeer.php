<?php



/**
 * Skeleton subclass for performing query and update operations on the 'profile' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.6.0-dev on:
 *
 * Wed Jul 13 23:36:49 2011
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model
 */
class ProfilePeer extends BaseProfilePeer {
  const PUBLIC_FLAG_SNS = 1;
  const PUBLIC_FLAG_FRIEND = 2;
  const PUBLIC_FLAG_PRIVATE = 3;
  const PUBLIC_FLAG_WEB = 4;

  protected $publicFlags = array(
    self::PUBLIC_FLAG_WEB     => 'All Users on the Web',
    self::PUBLIC_FLAG_SNS     => 'All Members',
    self::PUBLIC_FLAG_FRIEND  => '%my_friend%',
    self::PUBLIC_FLAG_PRIVATE => 'Private',
  );

  protected $nameByIds = array();

  public function getPublicFlags()
  {
    return array_map(array(sfContext::getInstance()->getI18N(), '__'), $this->publicFlags);
  }

  public function getPublicFlag($flag)
  {
    return sfContext::getInstance()->getI18N()->__($this->publicFlags[$flag]);
  }

  public function retrievesAll()
  {
    return $this->createQuery()->orderBy('sort_order')->execute();
  }

  public function retrieveByIsDispRegist()
  {
    return $this->createQuery()
      ->where('is_disp_regist = ?', true)
      ->orderBy('sort_order')
      ->execute();
  }

  public function retrieveByIsDispConfig()
  {
    return $this->createQuery()
      ->where('is_disp_config = ?', true)
      ->orderBy('sort_order')
      ->execute();
  }

  static public function retrieveByIsDispSearch()
  {
    return ProfileQuery::create()
      ->filterByIsDispSearch(true)
      ->orderBySortOrder()
      ->find();
  }

  // TODO: Use findOneByName
  public function retrieveByName($name)
  {
    return $this->createQuery()
      ->where('name = ?', $name)
      ->fetchOne();
  }

  public function getMaxSortOrder()
  {
    $result = $this->createQuery()
      ->orderBy('sort_order DESC')
      ->fetchOne();

    if ($result)
    {
      return (int)$result->getSortOrder();
    }

    return 0;
  }

  public function getProfileNameById($name)
  {
    if (isset($this->nameByIds[$name]))
    {
      return $this->nameByIds[$name];
    }

    $profile = $this->createQuery()
      ->select('id')
      ->where('name = ?', $name)
      ->fetchOne(array(), Doctrine::HYDRATE_NONE);

    if ($profile)
    {
      $this->nameByIds[$name] = $profile[0];
    }
    else
    {
      $this->nameByIds[$name] = null;
    }

    return $this->nameByIds[$name];
  }
} // ProfilePeer
