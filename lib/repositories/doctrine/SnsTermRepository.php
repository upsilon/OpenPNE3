<?php

class SnsTermRepository extends \Doctrine\ORM\EntityRepository implements ArrayAccess
{
  protected
    $culture = '',
    $application = '',
    $terms = null;

  public function configure($culture = '', $application = '')
  {
    if ($culture)
    {
      $this->culture = $culture;
    }

    if ($application)
    {
      $this->application = $application;
    }
  }

  public function retrieveByName($name)
  {
    $terms = $this->getTerms();
    $fronting = false;

    if (preg_match('/[A-Z]/', $name[0]))
    {
      $fronting = true;
      $name = strtolower($name[0]).substr($name, 1);
    }

    $result = (isset($terms[$name])) ? $terms[$name] : null;

    if ($result && $fronting)
    {
      $result->fronting();
    }

    return $result;
  }

  public function get($name)
  {
    return (!is_null($term = $this->retrieveByName($name))) ? $term : null;
  }

  public function set($name, $value, $culture = '', $application = '')
  {
    if (!$culture)
    {
      $culture = $this->culture;
    }

    if (!$application)
    {
      $application = $this->application;
    }

    if (!$culture || !$application)
    {
      return false;
    }

    $term = $this->createQuery()
      ->andWhere('name = ?', $name)
      ->andWhere('application = ?', $application)
//      ->andWhere('id IN (SELECT id FROM SnsTermTranslation WHERE lang = ?)', $culture)
      ->fetchOne();

    if (!$term)
    {
      $term = new SnsTerm();
      $term->setName($name);
      $term->setLang($culture);
      $term->setApplication($application);
    }
    $term->setValue($value);

    return $term->save();
  }

  protected function getTerms()
  {
    if (is_null($this->terms))
    {
      $this->terms = array();

      $q = $this->_em->createQueryBuilder()
        ->select('t')
        ->from('SnsTerm', 't');

      if ($this->application)
      {
        $q->where('t.application = :application');
        $q->setParameter('application', $this->application);
      }

      if ($this->culture)
      {
//        $q->where('id IN (SELECT id FROM SnsTermTranslation WHERE lang = ?)', $this->culture);
      }

      foreach ($q->getQuery()->getResult() as $term)
      {
        $this->terms[$term->getName()] = $term;
      }
    }

    return $this->terms;
  }

  public function offsetExists($offset)
  {
    return !is_null($this->get($offset));
  }

  public function offsetGet($offset)
  {
    return $this->get($offset);
  }

  public function offsetSet($offset, $value)
  {
    throw new LogicException('The SnsTermTable class is not writable.');
  }

  public function offsetUnset($offset)
  {
    throw new LogicException('The SnsTermTable class is not writable.');
  }
}
