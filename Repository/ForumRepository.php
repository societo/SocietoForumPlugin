<?php

namespace SocietoPlugin\Societo\ForumPlugin\Repository;

use Doctrine\ORM\EntityRepository;

class ForumRepository extends EntityRepository
{
    public function getForums($groupId = null, $limit = null, $offset = 0)
    {
        $qb = $this->getForumQuery($groupId);
        if (null !== $limit) {
            $qb->setFirstResult($offset)->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function getForumQuery($groupId = null)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('f')
            ->from('SocietoPlugin\Societo\ForumPlugin\Entity\Forum', 'f');

        if ($groupId) {
            $qb->where('f.group = :group')->setParameter('group', $groupId);
        } else {
            $qb->where('f.group IS NULL');
        }

        return $qb;
    }
}
