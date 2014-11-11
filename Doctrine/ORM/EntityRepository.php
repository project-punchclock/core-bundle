<?php
namespace Volleyball\Bundle\UtilityBundle\Doctrine\ORM;

class EntityRepository extends \Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository implements \Volleyball\Component\Utility\Interfaces\RepositoryInterface
{
    protected function getAlias()
    {
        return 'o';
    }
    
    public function search($query, $field = 'name')
    {
        return $this->getQueryBuilder()
            ->andWhere($this->getAlias().'.'.$field.' LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->getQuery()
            ->getResult();
    }
}
