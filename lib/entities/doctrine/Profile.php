<?php

/**
 * @Entity(repositoryClass="ProfileRepository")
 * @Table(name="profile")
 */
class Profile extends AbstractTranslatable
{
  const PUBLIC_FLAG_SNS = 1;
  const PUBLIC_FLAG_FRIEND = 2;
  const PUBLIC_FLAG_PRIVATE = 3;
  const PUBLIC_FLAG_WEB = 4;

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
   * @var boolean $is_required
   *
   * @Column(type="boolean")
   */
  private $is_required;

  /**
   * @var boolean $is_unique
   *
   * @Column(type="boolean")
   */
  private $is_unique;

  /**
   * @var boolean $is_edit_public_flag
   *
   * @Column(type="boolean")
   */
  private $is_edit_public_flag;

  /**
   * @var integer $default_public_flag
   *
   * @Column(type="integer", length=1)
   */
  private $default_public_flag;

  /**
   * @var string $form_type
   *
   * @Column(type="string", length=32)
   */
  private $form_type;

  /**
   * @var string $value_type
   *
   * @Column(type="string", length=32)
   */
  private $value_type;

  /**
   * @var boolean $is_disp_regist
   *
   * @Column(type="boolean")
   */
  private $is_disp_regist;

  /**
   * @var boolean $is_disp_config
   *
   * @Column(type="boolean")
   */
  private $is_disp_config;

  /**
   * @var boolean $is_disp_search
   *
   * @Column(type="boolean")
   */
  private $is_disp_search;

  /**
   * @var boolean $is_public_web
   *
   * @Column(type="boolean")
   */
  private $is_public_web;

  /**
   * @var string $value_regexp
   *
   * @Column(type="string", nullable=true)
   */
  private $value_regexp;

  /**
   * @var string $value_min
   *
   * @Column(type="string", length=32, nullable=true)
   */
  private $value_min;

  /**
   * @var string $value_max
   *
   * @Column(type="string", length=32, nullable=true)
   */
  private $value_max;

  /**
   * @var integer $sort_order
   *
   * @Column(type="integer", length=4, nullable=true)
   */
  private $sort_order;

  /**
   * @OneToMany(targetEntity="ProfileTranslation", mappedBy="profile", cascade={"persist", "remove"}, indexBy="lang")
   */
  protected $translations;


  public function getId()
  {
    return $this->id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getIsRequired()
  {
    return $this->is_required;
  }

  public function getIsUnique()
  {
    return $this->is_unique;
  }

  public function getIsEditPublicFlag()
  {
    return $this->is_edit_public_flag;
  }

  public function getDefaultPublicFlag()
  {
    return $this->default_public_flag;
  }

  public function getFormType()
  {
    return $this->form_type;
  }

  public function getValueType()
  {
    return $this->value_type;
  }

  public function getIsDispRegist()
  {
    return $this->is_disp_regist;
  }

  public function getIsDispConfig()
  {
    return $this->is_disp_config;
  }

  public function getIsDispSearch()
  {
    return $this->is_disp_search;
  }

  public function getIsPublicWeb()
  {
    return $this->is_public_web;
  }

  public function getValueRegexp()
  {
    return $this->value_regexp;
  }

  public function getValueMin()
  {
    return $this->value_min;
  }

  public function getValueMax()
  {
    return $this->value_max;
  }

  public function getSortOrder()
  {
    return $this->sort_order;
  }

  public function getCaption()
  {
    return $this->getTranslateColumn('caption');
  }

  public function getInfo()
  {
    return $this->getTranslateColumn('info');
  }

  public function toArray()
  {
    return array(
      'id' => $this->id,
      'name' => $this->name,
      'is_required' => $this->is_required,
      'is_unique' => $this->is_unique,
      'is_edit_public_flag' => $this->is_edit_public_flag,
      'default_public_flag' => $this->default_public_flag,
      'form_type' => $this->form_type,
      'value_type' => $this->value_type,
      'is_disp_regist' => $this->is_disp_regist,
      'is_disp_config' => $this->is_disp_config,
      'is_disp_search' => $this->is_disp_search,
      'is_public_web' => $this->is_public_web,
      'value_regexp' => $this->value_regexp,
      'value_min' => $this->value_min,
      'value_max' => $this->value_max,
      'sort_order' => $this->sort_order,
    );
  }

 /**
  * get options array
  *
  * @return array
  */
  public function getOptionsArray()
  {
    if ($this->isPreset())
    {
      return $this->getPresetOptionsArray();
    }

    $result = array();

    $options = $this->getProfileOption();

    foreach ($options as $option)
    {
      $result[$option->getId()] = $option->getValue();
    }

    return $result;
  }

 /**
  * get present options array
  *
  * @return array
  */
  public function getPresetOptionsArray()
  {
    $result = array();
    $config = $this->getPresetConfig();

    if (!empty($config['Choices']))
    {
      $result = array_combine($config['Choices'], $config['Choices']);
    }

    return $result;
  }

 /**
  * Checks if the profile is multiple select.
  *
  * @return boolean
  */
  public function isMultipleSelect()
  {
    return (bool)(('date' === $this->getFormType() && !$this->isPreset()) || 'checkbox' === $this->getFormType());
  }

 /**
  * Checks if the profile is single select.
  *
  * @return boolean
  */
  public function isSingleSelect()
  {
    return (bool)('radio' === $this->getFormType() || 'select' === $this->getFormType());
  }

 /**
  * Checks if the profile is preset.
  *
  * @return boolean
  */
  public function isPreset()
  {
    return (0 === strpos($this->getName(), 'op_preset_'));
  }

 /**
  * get raw preset name
  *
  * @return string
  */
  public function getRawPresetName()
  {
    if (!$this->isPreset())
    {
      return false;
    }

    $name = substr($this->getName(), strlen('op_preset_'));

    if ('region_select' === $this->getFormType()
        && 'string' !== $this->getValueType())
    {
      $name .= '_'.$this->getValueType();
    }

    return $name;
  }

 /**
  * get preset config
  *
  * @return array
  */
  public function getPresetConfig()
  {
    $list = opToolkit::getPresetProfileList();
    if (!empty($list[$this->getRawPresetName()]))
    {
      return $list[$this->getRawPresetName()];
    }

    return array();
  }

 /**
  * get profile options
  *
  * @return Doctrine_Collection
  */
  public function getProfileOption()
  {
    return Doctrine::getTable('ProfileOption')->createQuery()
      ->where('profile_id = ?', $this->id)
      ->orderBy('sort_order')
      ->execute();
  }

  public function getPublicFlags($isI18n = true)
  {
    $publicFlags = Doctrine::getTable('profile')->getPublicFlags($isI18n);
    if (!$this['is_public_web'])
    {
      unset($publicFlags[ProfileTable::PUBLIC_FLAG_WEB]);
    }

    return $publicFlags;
  }
}
