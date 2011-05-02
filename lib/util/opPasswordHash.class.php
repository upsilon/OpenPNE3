<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opPasswordHash
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kimura Youichi <kim.upsilon@bucyou.net>
 */
class opPasswordHash
{
  static public function generateHash($password)
  {
    $hasher = new PasswordHash(8, false);
    return $hasher->HashPassword($password);
  }

  static public function checkPassword($password, $hash)
  {
    if (0 !== strpos($hash, '$'))
    {
      // for backword compatibility.
      return $hash === md5($password);
    }

    $hasher = new PasswordHash(8, false);
    return (boolean)$hasher->CheckPassword($password, $hash);
  }
}
