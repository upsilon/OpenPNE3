<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMemberProfileSearchForm
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMemberProfileSearchForm extends BaseForm
{
  protected static $profileFieldPrefix = 'profile_';

  public function __construct($defaults = array(), $options = array())
  {
    parent::__construct($defaults, $options, false);
  }

  protected function getProfiles()
  {
    return Profile::find_all_by_is_disp_search(true);
  }

  public function configure()
  {
    $widgets = array();
    $validators = array();

    if ($this->getOption('is_use_id'))
    {
      $widgets += array('id' => new sfWidgetFormInputText());
      $validators += array('id' => new sfValidatorPass());
    }

    $widgets += array('name' => new sfWidgetFormInputText());
    $validators += array('name' => new opValidatorSearchQueryString(array('required' => false)));

    $culture = sfContext::getInstance()->getUser()->getCulture();

    foreach ($this->getProfiles() as $profile)
    {
      if (Profile::PUBLIC_FLAG_PRIVATE == $profile->default_public_flag && !$profile->is_edit_public_flag)
      {
        continue;
      }

      if ($profile->isPreset())
      {
        $config = $profile->getPresetConfig();
        $profileI18n['caption'] = sfContext::getInstance()->getI18n()->__($config['Caption']);
      }
      else
      {
        $profileI18n = $profile->translation[$culture]->attributes();
      }

      $profileWithI18n = $profile->attributes() + $profileI18n;

      $widget = opFormItemGenerator::generateSearchWidget($profileWithI18n, array('' => '') + $profile->getOptionsArray());
      if ($widget)
      {
        $widgets[self::$profileFieldPrefix.$profile->name] = $widget;
        $validators[self::$profileFieldPrefix.$profile->name] = new sfValidatorPass();
      }
    }

    $this->setWidgets($widgets);
    $this->setValidators($validators);

    $this->widgetSchema->setLabel('name', '%nickname%');

    $this->widgetSchema->setNameFormat('member[%s]');
  }

  protected function addIdColumnQuery(array &$query, $value)
  {
    if (!empty($value))
    {
      $query['conditions'][0][] = 'id = ?';
      $query['conditions'][] = $value;
    }
  }

  protected function addNameColumnQuery(array &$query, $value)
  {
    if (!empty($value))
    {
      if (is_array($value))
      {
        foreach ($value as $v)
        {
          $query['conditions'][0][] = 'name like ?';
          $query['conditions'][] = $v;
        }
      }
      else
      {
        if (!empty($value))
        {
          $query['conditions'][0][] = 'name like ?';
          $query['conditions'][] = $values;
        }
      }
    }
  }

  public function getFindParams()
  {
    $isWhere = false;
    $ids = null;
    $q = array();

    if ($this->getOption('is_use_id'))
    {
      $this->addIdColumnQuery($q, $this->getValue('id'));
    }

    $this->addNameColumnQuery($q, $this->getValue('name'));

    $profileValues = array();
    foreach ($this->getProfiles() as $profile)
    {
      $key = $profile->name;
      $value = $this->getValue(self::$profileFieldPrefix.$key);

      if (is_array($value))
      {
        $isEmpty = true;
        foreach ($value as $v)
        {
          if(!empty($v))
          {
            $isEmpty = false;
          }
        }
        if ($isEmpty)
        {
          $value = null;
        }
      }
      if (!empty($value))
      {
        $profileValues[$key] = $value;
        $isWhere = true;
      }
    }

//    $ids = Doctrine::getTable('MemberProfile')->searchMemberIds($profileValues, $ids, $this->getOption('is_check_public_flag', true));

    if ($isWhere)
    {
      if (!count($ids))
      {
        $ids[] = 0;
      }
      $q->whereIn('id', $ids);
    }

    return $q;
 }
}

