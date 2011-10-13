<?php

class ProfileRepository extends \Doctrine\ORM\EntityRepository
{
  public function getIdByName($profileName)
  {
    static $ids = array();
    if (!isset($ids[$profileName]))
    {
      $profile = $this->findOneBy(array('name' => $profileName));
      if (!$profile)
      {
        return null;
      }

      $ids[$profileName] = $profile->getId();
    }

    return $ids[$profileName];
  }
}
