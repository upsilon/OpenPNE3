<?php

class NavigationRepository extends \Doctrine\ORM\EntityRepository
{
  public function retrieveByType($type)
  {
    return $this->_em->createQueryBuilder()
      ->select('n')
      ->from('Navigation', 'n')
      ->where('n.type = :type')
      ->setParameter('type', $type)
      ->orderBy('n.sort_order')
      ->getQuery()->getResult();
  }
}
