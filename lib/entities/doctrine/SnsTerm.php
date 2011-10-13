<?php

/**
 * @Entity(repositoryClass="SnsTermRepository")
 * @Table(name="sns_term")
 */
class SnsTerm extends AbstractTranslatable
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
   * @var string $application
   *
   * @Column(type="string", length=32)
   */
  private $application;

  /**
   * @OneToMany(targetEntity="SnsTermTranslation", mappedBy="sns_term", cascade={"persist", "remove"}, indexBy="lang")
   */
  protected $translations;

  protected $process = array(
    'withArticle' => false,
    'pluralize' => false,
    'fronting' => false,
    'titleize' => false,
  );


  public function getId()
  {
    return $this->id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getApplication()
  {
    return $this->application;
  }

  public function getLang()
  {
    return $this->getTranslateColumn('lang');
  }

  public function getValue()
  {
    return $this->getTranslateColumn('value');
  }

  public function doFronting($string)
  {
    if ('en' === $this->getLang())
    {
      $string = strtoupper($string[0]).substr($string, 1);
    }

    return $string;
  }

  public function doTitleize($string)
  {
    if ('en' === $this->getLang())
    {
      $words = array_map('ucfirst', explode(' ' ,$string));
      $string = implode(' ', $words);
    }

    return $string;
  }

  public function doPluralize($string)
  {
    if ('en' === $this->getLang())
    {
      $string = opInflector::pluralize($string);
    }

    return $string;
  }

  public function doWithArticle($string)
  {
    if ('en' === $this->getLang())
    {
      $string = opInflector::getArticle($string).' '.$string;
    }

    return $string;
  }

  public function __toString()
  {
    $value = $this->translations[$this->getLang()]->getValue();

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
