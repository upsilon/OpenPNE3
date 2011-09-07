<?php

class Banner extends ActiveRecord\Model
{
  static $table_name = 'banner';

  public function getRandomImage()
  {
return false;
    $bannerUseImage = Doctrine::getTable('BannerUseImage')->createQuery($this->getId())
      ->where('banner_id = ?', $this->getId())
      ->orderBy(Doctrine_Manager::connection()->expression->random())
      ->limit(1)
      ->fetchOne();

    if (!$bannerUseImage)
    {
      return false;
    }

    return $bannerUseImage->getBannerImage();
  }
}
