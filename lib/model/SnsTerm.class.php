<?php

class SnsTerm extends ActiveRecord\Model
{
  static $table_name = 'sns_term';

  static $has_many = array(
    array(
      'translation',
      'foreign_key' => 'id', 'class' => 'SnsTermTranslation', 'index_by' => 'lang',
    ),
  );

  protected $process = array(
    'withArticle' => false,
    'pluralize' => false,
    'fronting' => false,
    'titleize' => false,
  );

  protected $currentLang = null;

  public function setCurrentLanguage($lang)
  {
    $this->currentLang = $lang;
  }

  public function getCurrentLanguage()
  {
    if ($this->currentLang)
    {
      return $this->currentLang;
    }

    return sfContext::getInstance()->getUser()->getCulture();
  }

  public function doFronting($string)
  {
    if ('en' === $this->getCurrentLanguage())
    {
      $string = strtoupper($string[0]).substr($string, 1);
    }

    return $string;
  }

  public function doTitleize($string)
  {
    if ('en' === $this->getCurrentLanguage())
    {
      $words = array_map('ucfirst', explode(' ' ,$string));
      $string = implode(' ', $words);
    }

    return $string;
  }

  public function doPluralize($string)
  {
    if ('en' === $this->getCurrentLanguage())
    {
      $string = opInflector::pluralize($string);
    }

    return $string;
  }
  public function doWithArticle($string)
  {
    if ('en' === $this->getCurrentLanguage())
    {
      $string = opInflector::getArticle($string).' '.$string;
    }

    return $string;
  }

  public function __toString()
  {
    $value = $this->translation[$this->getCurrentLanguage()]->value;

    foreach ($this->process as $k => $v)
    {
      if ($v)
      {
        $method = 'do'.ucfirst($k);
        $value = $this->$method($value);
      }

      $this->process[$k] = false;
    }

    return htmlspecialchars($value, ENT_QUOTES, sfConfig::get('sf_charset'));
  }

  public function __call($name, $args)
  {
    if (isset($this->process[$name]))
    {
      $this->process[$name] = true;

      return $this;
    }

    return parent::__call($name, $args);
  }
}
