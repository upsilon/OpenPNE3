<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * MemberConfigGravatar form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kimura Youichi <kim.upsilon@bucyou.net>
 */
class MemberConfigGravatarForm extends MemberConfigForm
{
  protected $category = 'gravatar';

  public function __construct(Member $member = null, $options = array(), $CSRFSecret = null)
  {
    parent::__construct($member, $options, $CSRFSecret);

    if (null === $this->member->getEmailAddress(false))
    {
      $this->widgetSchema->setHelp('enable_gravatar', 'E-mail address is not set.');
      $this->widgetSchema['enable_gravatar']->setAttribute('disabled', 'disabled');
    }
  }
}
