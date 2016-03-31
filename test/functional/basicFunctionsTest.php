<?php

require_once dirname(__FILE__).'/../../config/ProjectConfiguration.class.php';

$frontendConfiguration = ProjectConfiguration::getApplicationConfiguration('pc_frontend', 'test', true);
sfContext::createInstance($frontendConfiguration);

$backendConfiguration = ProjectConfiguration::getApplicationConfiguration('pc_backend', 'test', true);
sfContext::createInstance($backendConfiguration);

$fixture = '../../data/fixtures';
require_once dirname(__FILE__).'/../bootstrap/database.php';

$tester = new opTestFunctional(new opBrowser());

if (!in_array('opCommunityTopicPlugin', $frontendConfiguration->getPlugins(), true))
{
  $tester->test()->fail('opCommunityTopicPlugin is required.');
  die(1);
}

opMailSend::initialize();
Zend_Mail::setDefaultTransport(new opZendMailTransportMock());

sfContext::switchTo('pc_frontend');

$tester
  ->login('sns@example.com', 'password')

  ->info('コミュニティ作成')

  ->get('/')
  ->checkDispatch('member', 'home')
  ->isStatusCode(200)

  ->click('コミュニティ検索')
  ->checkDispatch('community', 'search')
  ->isStatusCode(200)

  ->click('コミュニティ作成')
  ->checkDispatch('community', 'edit')
  ->isStatusCode(200)

  ->click('送信', array(
    'community' => array(
      'name' => 'Community A',
    ),
    'community_config' => array(
      'description' => 'hogehoge',
      'is_send_pc_joinCommunity_mail' => '0',
    ),
  ))
  ->checkDispatch('community', 'edit')
  ->followRedirect()
  ->checkDispatch('community', 'home')
  ->isStatusCode(200)

  ->info('コミュニティトピック投稿')

  ->get('/')
  ->checkDispatch('member', 'home')
  ->isStatusCode(200)

  ->click('Community A (1)')
  ->checkDispatch('community', 'home')
  ->isStatusCode(200)

  ->click('トピックを作成する')
  ->checkDispatch('communityTopic', 'new')
  ->isStatusCode(200)

  ->click('送信', array(
    'community_topic' => array(
      'name' => 'Topic A',
      'body' => 'tetete',
    ),
  ))
  ->checkDispatch('communityTopic', 'create')
  ->followRedirect()
  ->checkDispatch('communityTopic', 'show')
  ->isStatusCode(200)
;

$results = lime_test::to_array();
exit(count($results[0]['stats']['failed']) === 0 ? 0 : 1);
