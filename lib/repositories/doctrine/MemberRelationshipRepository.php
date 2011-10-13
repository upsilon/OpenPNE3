<?php

class MemberRelationshipRepository extends \Doctrine\ORM\EntityRepository
{
  public function getFriends($memberId, $limit = null, $isRandom = false)
  {
    $collection = new \Doctrine\Common\Collections\ArrayCollection;
    $friendIds = $this->getFriendMemberIds($memberId);

    if ($isRandom)
    {
      shuffle($friendIds);
    }

    $limitedFriendIds = is_null($limit) ? $friendIds : array_slice($friendIds, 0, $limit);

    foreach ($limitedFriendIds as $friendId)
    {
      $collection[] = $this->_em->find('Member', $friendId);
    }

    return $collection;
  }

  public function getFriendMemberIds($memberId)
  {
    $qb = $this->createQueryBuilder('mr');

    return $qb
      ->select('mr.member_id_to')
      ->where($qb->expr()->eq('mr.member_id_from', $memberId))
      ->where($qb->expr()->eq('mr.is_friend', 'true'))
//      ->where($qb->expr()->eq('mr.Member.is_active', 'true'))
      ->getQuery()
      ->getScalarResult();
  }
}
