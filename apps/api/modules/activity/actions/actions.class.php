<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * activity actions.
 *
 * @package    OpenPNE
 * @subpackage action
 * @author     Kimura Youichi <kim.upsilon@gmail.com>
 */
class activityActions extends opJsonApiActions
{
  public function executeSearch(sfWebRequest $request)
  {
    $viewerId = $this->getUser()->getMemberId();

    $conn = Doctrine_Core::getTable('ActivityData')->getConnection();

    $query = 'SELECT a.id, body, uri, in_reply_to_activity_id, source, source_uri, a.created_at,'
           . '  m.id AS member_id, m.name,'
           . '  mr.is_friend, mr.is_access_block'
           . ' FROM activity_data a'
           . ' LEFT JOIN member m ON m.id = a.member_id AND m.is_active <> 0'
           . ' LEFT OUTER JOIN member_relationship mr ON mr.member_id_from = :viewerId AND mr.member_id_to = a.member_id';
    $params = array('viewerId' => $viewerId);

    if (isset($request['target']))
    {
      if ('friend' === $request['target'])
      {
        $this->forward400('Not implemented.');
      }
      elseif ('community' === $target['community'])
      {
        $this->forward400('Not implemented.');
      }
      else
      {
        $this->forward400('target parameter is invalid.');
      }
    }
    else
    {
      if (isset($request['member_id']))
      {
        $this->forward400('Not implemented.');
      }
      else
      {
      }
    }

    if (isset($request['max_id']))
    {
      $query .= '  AND id <= :maxId';
      $params['maxId'] = $request['max_id'];
    }

    if (isset($request['since_id']))
    {
      $query .= '  AND id > :sinceId';
      $params['sinceId'] = $request['since_id'];
    }

    if (isset($request['activity_id']))
    {
      $query .= '  AND id = :id';
      $params['id'] = $request['activity_id'];
    }

    if (isset($request['keyword']))
    {
      $query .= '  AND body LIKE bodyPattern';
      $params['bodyPattern'] = '%'.$request['keyword'].'%';
    }

    $query .= '  AND in_reply_to_activity_id IS NULL';

    $globalAPILimit = sfConfig::get('op_json_api_limit', 20);
    if (isset($request['count']) && (int)$request['count'] < $globalAPILimit)
    {
      $query .= ' LIMIT '.((int)$request['count']);
    }
    else
    {
      $query .= ' LIMIT '.((int)$globalAPILimit);
    }

    $stmt = $conn->execute($query, $params);

    $results = array();
    while ($row = $stmt->fetch(Doctrine_Core::FETCH_ASSOC))
    {
      $activity = array(
        'id' => $row['id'],
        'member' => array(
          'id' => $row['member_id'],
          'name' => $row['name'],
          'profile_url' => sfContext::getInstance()->getConfiguration()
            ->generateAppUrl('pc_frontend', array('sf_route' => 'obj_member_profile', 'id' => $row['member_id']), true),
          'friend' => '1' === $row['is_friend'],
          'blocking' => '1' === $row['is_access_block'],
          'self' => $row['member_id'] === $viewerId,
        ),
        'body' => $row['body'],
        'body_html' => sfOutputEscaper::escape(sfConfig::get('sf_escaping_method'), $row['body']), // fixme
        'uri' => $row['uri'],
        'source' => $row['source'],
        'source_uri' => $row['source_uri'],
        'created_at' => date('r', strtotime($row['created_at'])),
      );

      $results[] = $activity;
    }

    return $this->renderJSON(array('status' => 'success', 'data' => $results));
  }

  public function executeMember(sfWebRequest $request)
  {
    if ($request['id'])
    {
      $request['member_id'] = $request['id'];
    }

    if (isset($request['target']))
    {
      unset($request['target']);
    }

    $this->forward('activity', 'search');
  }

  public function executeFriends(sfWebRequest $request)
  {
    $request['target'] = 'friend';

    if (isset($request['member_id']))
    {
      $request['target_id'] = $request['member_id'];
      unset($request['member_id']);
    }
    elseif (isset($request['id']))
    {
      $request['target_id'] = $request['id'];
      unset($request['id']);
    }

    $this->forward('activity', 'search');
  }

  public function executeCommunity(sfWebRequest $request)
  {
    $request['target'] = 'community';

    if (isset($request['community_id']))
    {
      $request['target_id'] = $request['community_id'];
      unset($request['community_id']);
    }
    elseif (isset($request['id']))
    {
      $request['target_id'] = $request['id'];
      unset($request['id']);
    }
    else
    {
      $this->forward400('community_id parameter not specified.');
    }

    $this->forward('activity', 'search');
  }

  public function executePost(sfWebRequest $request)
  {
    $body = (string)$request['body'];
    $this->forward400If('' === $body, 'body parameter not specified.');
    $this->forward400If(mb_strlen($body) > 140, 'The body text is too long.');

    $memberId = $this->getUser()->getMemberId();
    $options = array();

    if (isset($request['public_flag']))
    {
      $options['public_flag'] = $request['public_flag'];
    }

    if (isset($request['in_reply_to_activity_id']))
    {
      $options['in_reply_to_activity_id'] = $request['in_reply_to_activity_id'];
    }

    if (isset($request['uri']))
    {
      $options['uri'] = $request['uri'];
    }
    elseif (isset($request['url']))
    {
      $options['uri'] = $request['url'];
    }

    if (isset($request['target']) && 'community' === $request['target'])
    {
      if (!isset($request['target_id']))
      {
        $this->forward400('target_id parameter not specified.');
      }

      $options['foreign_table'] = 'community';
      $options['foreign_id'] = $request['target_id'];
    }

    $options['source'] = 'API';

    $imageFiles = $request->getFiles('images');
    if (!empty($imageFiles))
    {
      foreach ((array)$imageFiles as $imageFile)
      {
        $validator = new opValidatorImageFile(array('required' => false));
        try
        {
          $obj = $validator->clean($imageFile);
        }
        catch (sfValidatorError $e)
        {
          $this->forward400('This image file is invalid.');
        }
        if (is_null($obj))
        {
          continue;
        }
        $file = new File();
        $file->setFromValidatedFile($obj);
        $file->setName('ac_'.$this->getUser()->getMemberId().'_'.$file->getName());
        $file->save();
        $options['images'][] = array('file_id' => $file->getId());
      }
    }

    $this->activity = Doctrine::getTable('ActivityData')->updateActivity($memberId, $body, $options);

    if ('1' === $request['forceHtml'])
    {
      // workaround for some browsers (see #3201)
      $this->getRequest()->setRequestFormat('html');
      $this->getResponse()->setContentType('text/html');
    }

    $this->setTemplate('object');
  }

  public function executeDelete(sfWebRequest $request)
  {
    if (isset($request['activity_id']))
    {
      $activityId = $request['activity_id'];
    }
    elseif (isset($request['id']))
    {
      $activityId = $request['id'];
    }
    else
    {
      $this->forward400('activity_id parameter not specified.');
    }

    $activity = Doctrine::getTable('ActivityData')->find($activityId);

    $this->forward404Unless($activity, 'Invalid activity id.');

    $this->forward403Unless($activity->getMemberId() === $this->getUser()->getMemberId());

    $activity->delete();

    return $this->renderJSON(array('status' => 'success'));
  }

  public function executeMentions(sfWebRequest $request)
  {
    $builder = opActivityQueryBuilder::create()
      ->setViewerId($this->getUser()->getMemberId())
      ->includeMentions();

    $query = $builder->buildQuery()
      ->andWhere('in_reply_to_activity_id IS NULL')
      ->andWhere('foreign_table IS NULL')
      ->andWhere('foreign_id IS NULL')
      ->limit(20);

    $this->activityData = $query->execute();

    $this->setTemplate('array');
  }
}
