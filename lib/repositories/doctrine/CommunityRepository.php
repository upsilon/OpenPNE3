<?php

class CommunityRepository extends \Doctrine\ORM\EntityRepository
{
  public function retrievesByMemberId($memberId, $limit = 5, $isRandom = false)
  {
    $qb = $this->_em->getRepository('CommunityMember')->createQueryBuilder('cm');

    $communityMembers = $qb
      ->select('cm.community_id')
      ->where($qb->expr()->eq('cm.is_pre', false))
      ->where($qb->expr()->eq('cm.member_id', $memberId))
      ->getQuery()
      ->getScalarResult();

    $ids = array();
    foreach ($communityMembers as $communityMember)
    {
      $ids[] = $communityMember[0];
    }

    if (empty($ids))
    {
      return;
    }

    $q = $this->createQueryBuilder();
    $q->where($q->expr()->in('id', $ids));

    if (!is_null($limit))
    {
      $q->limit($limit);
    }

    if ($isRandom)
    {
      $expr = new Doctrine_Expression('RANDOM()');
      $q->orderBy($expr);
    }

    return $q->getQuery()->execute();
  }

  public function getPositionRequestCommunitiesQuery($position = 'admin', $memberId = null)
  {
    if (null === $memberId)
    {
      $memberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    $communityMemberPositions = $this->_em->getRepository('CommunityMemberPosition')
      ->findBy(array('member_id' => $memberId, 'name' => $position.'_confirm'));

    if (!$communityMemberPositions || !count($communityMemberPositions))
    {
      return null;
    }

    $qb = $this->createQueryBuilder();

    return $qb
      ->where($qb->expr()->in('id', array_values($communityMemberPositions->toKeyValueArray('id', 'community_id'))));
  }

  public function getPositionRequestCommunities($position = 'admin', $memberId = null)
  {
    $q = $this->getPositionRequestCommunitiesQuery($position, $memberId);
    return $q ? $q->getQuery()->getResults() : null;
  }

  public function countPositionRequestCommunities($position = 'admin', $memberId = null)
  {
    $q = $this->getPositionRequestCommunitiesQuery($position, $memberId);
    return $q ? $q->select('COUNT(*)')->getQuery()->getSingleScalarResult() : null;
  }
}
