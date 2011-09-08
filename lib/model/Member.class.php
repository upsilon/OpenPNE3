<?php

class Member extends ActiveRecord\Model
{
  static $table_name = 'member';

  static $has_many = array(
    array(
      'friends',
      'through' => 'member_relationship', 'conditions' => 'member_relationship.is_friend = 1', 'class' => 'Member',
    ),
    'member_image',
    'member_profile',
    'member_config',
  );

  static $belongs_to = array(
    array(
      'invite_member',
      'primary_key' => 'invite_member_id', 'class' => 'Member',
    ),
  );

  protected $configs = array();

  public function getConfig($name, $default = null)
  {
    if (isset($this->configs[$name]))
    {
      return $this->configs[$name];
    }

    foreach ($this->member_config as $config)
    {
      if ($name == $config->name)
      {
        $this->configs[$name] =& $config;

        return $config->value;
      }
    }

    return $default;
  }

  public function setConfig($name, $value, $isDateTime = false)
  {
    $config = MemberConfig::find_or_create_by_member_id_and_name($this->id, $name);

    if ($isDateTime)
    {
      $config->value_datetime = $value;
    }
    else
    {
      $config->value = $value;
    }

    $config->save();
  }

  public function updateLastLoginTime()
  {
    $this->setConfig('lastLogin', date('Y-m-d H:i:s'), true);
  }

  public function getLastLoginTime()
  {
    return $this->getConfig('lastLogin');
  }

  public function isOnBlacklist()
  {
    $mobile_uid = $this->getConfig('mobile_uid');
    if ($mobile_uid)
    {
      return (bool)Blacklist::find_by_uid($mobile_uid);
    }

    return false;
  }

  public function getProfile($profileName, $viewableCheck = false, $myMemberId = null)
  {
    $profileId = Profile::getProfileId($profileName);
    $profile = MemberProfile::find_by_profile_id_and_member_id($profileId, $this->id);

    if (!$profile)
    {
      return null;
    }

    if ($viewableCheck)
    {
      if ($myMemberId)
      {
        $member = Member::find($myMemberId);
      }
      else
      {
        $member = sfContext::getInstance()->getUser()->getMember();
      }

      if (!$member)
      {
        return null;
      }

//      if (!$profile->isAllowed($member, 'view'))
//      {
//        return null;
//      }
    }

    return $profile;
  }

  public function getImage()
  {
    return MemberImage::find_by_member_id($this->id, array('order' => 'is_primary DESC'));
  }

  public function getImageFileName()
  {
    $image = $this->getImage();
    if ($image)
    {
      return $image->file;
    }

    return false;
  }
}
