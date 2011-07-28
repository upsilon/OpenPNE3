<?php



/**
 * Skeleton subclass for performing query and update operations on the 'member_profile' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.6.0-dev on:
 *
 * Wed Jul 13 23:36:50 2011
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model
 */
class MemberProfilePeer extends BaseMemberProfilePeer {
  public function getProfileListByMemberId($memberId)
  {
    $profiles = Doctrine::getTable('Profile')->createQuery()
      ->select('id')
      ->orderBy('sort_order')
      ->execute(array(), Doctrine::HYDRATE_NONE);

    $queryCacheHash = '';

    $q = $this->createQuery()
      ->where('member_id = ?')
      ->andWhere('profile_id = ?');

    $memberProfiles = array();
    foreach ($profiles as $profile)
    {
      if ($queryCacheHash)
      {
        $q->setCachedQueryCacheHash($queryCacheHash);
      }

      $memberProfile = $q->fetchOne(array($memberId, $profile[0]));
      if ($memberProfile)
      {
        $memberProfiles[] = $memberProfile;
      }

      if (!$queryCacheHash)
      {
        $queryCacheHash = $q->calculateQueryCacheHash();
      }
    }

    // NOTICE: this returns Array not Doctrine::Collection
    return $memberProfiles;
  }

  public function getViewableProfileListByMemberId($memberId, $myMemberId = null)
  {
    if (is_null($myMemberId))
    {
      $myMemberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    $profiles = $this->getProfileListByMemberId($memberId);
    foreach ($profiles as $key => $profile)
    {
      if (!$profile->isViewable($myMemberId))
      {
        unset($profiles[$key]);
      }
    }

    return $profiles;
  }

  public function getViewableProfileByMemberIdAndProfileName($memberId, $profileName, $myMemberId = null)
  {
    if (is_null($myMemberId))
    {
      $myMemberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    $profile = $this->retrieveByMemberIdAndProfileName($memberId, $profileName);

    if ($profile && $profile->isViewable($myMemberId))
    {
      return $profile;
    }

    return false;
  }

  public function retrieveByMemberIdAndProfileId($memberId, $profileId)
  {
    return $this->createQuery()
      ->where('member_id = ?', $memberId)
      ->andWhere('profile_id = ?', $profileId)
      ->fetchOne();
  }

  public function retrieveByMemberIdAndProfileName($memberId, $profileName)
  {
    $profileId = ProfileQuery::create()->findOneByName($profileName)->getId();
    if ($profileId)
    {
      return MemberProfileQuery::create()
        ->filterByMemberId($memberId)
        ->filterByProfileId($profileId)
        ->findOne();
    }

    return null;
  }

  static public function searchMemberIds($profile = array(), $ids = null, $isCheckPublicFlag = true)
  {
    $publicFlag = ($isCheckPublicFlag) ? 1 : null;

    foreach ($profile as $key => $value)
    {
      $item = ProfileQuery::create()->findOneByName($key);
      $_result = array();
      $column = 'value';

      if ($item->isPreset())
      {
        if ($item->getFormType() === 'date')
        {
          $dateValue = $value;
          foreach ($dateValue as $k => $v)
          {
            if (!$v)
            {
              $dateValue[$k] = '%';
              continue;
            }

            if ($dateValue !== 'year')
            {
              $dateValue[$k] = sprintf('%02d', $dateValue[$k]);
            }
          }

          $value = implode('-', $dateValue);
        }
      }
      elseif ($item->getFormType() === 'date')
      {
        $options = $item->getProfileOption();
        $i = 0;
        foreach ($value as $k => $v)
        {
          $option = $options[$i++];
          if ($v)
          {
            $ids = self::filterMemberIdByProfileOption($ids, $column, $v, $option, $publicFlag);
          }
        }
        continue;
      }
      elseif ($item->isMultipleSelect() || $item->isSingleSelect())
      {
        $column = 'profile_option_id';
      }

      $ids = $this->filterMemberIdByProfile($ids, $column, $value, $item, $publicFlag);
    }

    return $ids;
  }

  static public function filterMemberIdByProfile($ids, $column, $value, Profile $item, $publicFlag = 1)
  {
    $_result = array();
    $q = MemberProfileQuery::create('m');
    $q = opFormItemGenerator::filterSearchQuery($q, 'm.'.$column, $value, $item->toArray())
      ->select('m.member_id')
      ->andWhere('m.profile_id = ?', $item->getId());

    $isCheckPublicFlag = is_integer($publicFlag);
    if (!$item->getIsEditPublicFlag())
    {
      if (
        ProfilePeer::PUBLIC_FLAG_SNS == $item->getDefaultPublicFlag()
        || ProfilePeer::PUBLIC_FLAG_WEB == $item->getDefaultPublicFlag()
      )
      {
        $isCheckPublicFlag = false;
      }
      else
      {
        return array();
      }
    }
    if ($isCheckPublicFlag)
    {
      $publicFlags = (array)$publicFlag;
      if (1 == $publicFlag)
      {
        $publicFlags[] = 4;
      }

      if ($item->isMultipleSelect() && $item->getFormType() !== 'date')
      {
        $q->innerJoin('m.MemberProfile pm')
          ->where('pm.public_flag', $publicFlags);
      }
      else
      {
        $q->filterBy('m.public_flag', $publicFlags);
      }
    }

    $list = $q->find();

    foreach ($list as $v)
    {
      $_result[] = $v->getMemberId();
    }

    if (is_array($ids))
    {
      $ids = array_values(array_intersect($ids, $_result));
    }
    else
    {
      $ids = array_values($_result);
    }

    if ($isCheckPublicFlag && 'op_preset_birthday' === $item->getName())
    {
      if ('%-' !== substr($value, 0, 2))  // "year" part is specified
      {
        $ids = $this->filterMemberIdsByAgePublicFlag($ids);
      }
    }

    return $ids;
  }

  /**
   * Filters member ids of the specified array by checking a value of "age_public_flag" configuration
   *
   * Currently, this method has the following LIMITATIONS for performance reason:
   *
   *   - This method can't handle a custom "public_flag" condition (only "All Members" and "All Users on the Web")
   *   - This method is impelemented on the assumption that the default value of "age_public_flag" is "All Members"
   *
   * @param  array $ids
   * @return array
   */
  static protected function filterMemberIdsByAgePublicFlag(array $ids)
  {
    $memberConfigSettings = sfConfig::get('openpne_member_config');
    $choises = $memberConfigSettings['age_public_flag']['Choices'];

    $ignores = array();
    foreach ($choises as $k => $v)
    {
      // skip "All Members" and "All Users on the Web"
      if (1 == $k || 4 == $k)
      {
        continue;
      }

      $ignores[] = MemberConfigPeer::generateNameValueHash('age_public_flag', $k);
    }

    $rs = MemberConfigQuery::create()
      ->select('member_id')
      ->filterByNameValueHash($ignores)
      ->find();

    $ignoreMemberIds = array();
    foreach ($rs as $r)
    {
      $ignoreMemberIds[] = $r->getMemberId();
    }

    $ids = array_diff($ids, $ignoreMemberIds);

    return $ids;
  }

  static public function filterMemberIdByProfileOption($ids, $column, $value, ProfileOption $item, $publicFlag = 1)
  {
    $_result = array();

    $q = MemberProfileQuery::create('m')
      ->select('m.member_id')
      ->filterBy($column, $value)
      ->filterByProfileOptionId($item->getId());

    if (is_integer($publicFlag))
    {
      $q->addFrom('MemberProfile pm')
        ->where('m.tree_key = pm.id')
        ->where('pm.public_flag <= ?', $publicFlag);
    }

    $list = $q->find();

    foreach ($list as $value)
    {
      $_result[] = $value->getMemberId();
    }

    if (is_array($ids))
    {
      $ids = array_values(array_intersect($ids, $_result));
    }
    else
    {
      $ids = array_values($_result);
    }

    return $ids;
  }

  public function createChild(Doctrine_Record $parent, $memberId, $profileId, $optionIds, $values = array())
  {
    $parent->clearChildren();

    foreach ($optionIds as $i => $optionId)
    {
      $childProfile = new MemberProfile();
      $childProfile->setMemberId($memberId);
      $childProfile->setProfileId($profileId);
      $childProfile->setProfileOptionId($optionId);
      if (isset($values[$i]))
      {
        $childProfile->setValue($values[$i]);
      }
      $childProfile->getNode()->insertAsLastChildOf($parent);
      $childProfile->save();
    }
  }
} // MemberProfilePeer
