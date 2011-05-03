<?php

include_once dirname(__FILE__).'/../../bootstrap/unit.php';

$t = new lime_test(6, new lime_output_color());

$t->diag('opPasswordHash::generateHash()');
sfConfig::set('op_password_through_phpass', true);
if (1 === CRYPT_BLOWFISH)
{
  $t->is(strlen(opPasswordHash::generateHash('aaaaaaa')), 60, 'Use CRYPT_BLOWFISH');
}
elseif (1 === CRYPT_EXT_DES)
{
  $t->is(strlen(opPasswordHash::generateHash('aaaaaaa')), 20, 'Use CRYPT_EXT_DES');
}
else
{
  $t->is(strlen(opPasswordHash::generateHash('aaaaaaa')), 34, 'Use MD5-based salted and stretched hash');
}
sfConfig::set('op_password_through_phpass', false);
$t->is(opPasswordHash::generateHash('aaaaaaa'), md5('aaaaaaa'), 'Use MD5');
sfConfig::set('op_password_through_phpass', true);

$t->diag('opPasswordHash::checkPassword()');
$hash = opPasswordHash::generateHash('aaaaaaa');
$t->ok(opPasswordHash::checkPassword('aaaaaaa', $hash), 'Correct password (using phpass)');
$t->ok(!opPasswordHash::checkPassword('bbbbbbb', $hash), 'Wrong password (using phpass)');
$hash = md5('aaaaaaa');
$t->ok(opPasswordHash::checkPassword('aaaaaaa', $hash), 'Correct password (for old password hashes)');
$t->ok(!opPasswordHash::checkPassword('bbbbbbb', $hash), 'Wrong password (for old password hashes)');

