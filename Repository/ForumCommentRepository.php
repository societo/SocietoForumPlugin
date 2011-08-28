<?php

namespace SocietoPlugin\Societo\ForumPlugin\Repository;

use Doctrine\ORM\EntityRepository;

class ForumCommentRepository extends EntityRepository
{
    public function getAllByForumId($forumId, $limit = null, $offset = 0)
    {
        $builder = $this->getAllByForumIdQuery($forumId);

        if (null !== $limit) {
            $builder->setFirstResult($offset)->setMaxResults($limit);
        }

        $q = $builder->getQuery();

        $results = $q->getResult();

        return $results;
    }

    public function getAllByForumIdQuery($forumId)
    {
        $builder = $this->_em->createQueryBuilder();
        $builder->select('f')
            ->from('SocietoPlugin\Societo\ForumPlugin\Entity\ForumComment', 'f')
            ->where('f.forum = :id')
            ->setParameter('id', $forumId)
            ->add('orderBy', 'f.createdAt DESC')
        ;

        return $builder;
    }

    public function countByForumId($forumId)
    {
        $builder = $this->_em->createQueryBuilder();
        $builder->select($builder->expr()->count('f.id'))
            ->from('SocietoPlugin\Societo\ForumPlugin\Entity\ForumComment', 'f')
            ->where('f.forum = :id')
            ->setParameter('id', $forumId)
        ;

        return $builder->getQuery()->getSingleScalarResult();
    }
}
