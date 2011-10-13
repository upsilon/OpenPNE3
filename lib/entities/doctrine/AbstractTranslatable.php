<?php

/**
 * @MappedSuperclass
 */
abstract class AbstractTranslatable
{
  public function __construct()
  {
    $this->translations = new \Doctrine\Common\Collections\ArrayCollection;
  }

  public function getTranslation($lang = null)
  {
    if (null !== $lang)
    {
      return $this->translations[$lang];
    }

    $lang = sfContext::getInstance()->getUser()->getCulture();
    if (isset($this->translations[$lang]))
    {
      return $this->translations[$lang];
    }
    if (isset($this->translations['en']))
    {
      return $this->translations['en'];
    }

    return null;
  }

  protected function getTranslateColumn($columnName)
  {
    $method = 'get'.ucfirst($columnName);

    return $this->getTranslation()->$method();
  }
}
