<?php

class CommunityMemberRepository extends \Doctrine\ORM\EntityRepository
{
  public function getCommunityIdsOfAdminByMemberId($memberId)
  {
    $objects = $this->_em->getRepository('CommunityMemberPosition')
      ->findBy(array('member_id' => $memberId, 'name' => 'admin'));

    $results = array();
    foreach ($objects as $obj)
    {
      $results[] = $obj->getCommunityId();
    }
    return $results;
  }

  public function getCommunityMembersPreQuery($memberId)
  {
    $adminCommunityIds = $this->getCommunityIdsOfAdminByMemberId($memberId);

    if (count($adminCommunityIds))
    {
      $qb = $this->createQueryBuilder();

      return $qb
        ->where($qb->expr()->in('community_id', $adminCommunityIds))
        ->where($qb->expr()->eq('is_pre', true));
    }

    return false;
  }

  public function getCommunityMembersPre($memberId)
  {
    $q = $this->getCommunityMembersPreQuery($memberId);

    if (!$q)
    {
      return array();
    }

    return $q->getQuery()->execute();
  }

  public function countCommunityMembersPre($memberId)
  {
    $q = $this->getCommunityMembersPreQuery($memberId);
    if (!$q)
    {
      return 0;
    }

    return $q->select('COUNT(*)')->getQuery()->getSingleScalarResult();
  }
}
