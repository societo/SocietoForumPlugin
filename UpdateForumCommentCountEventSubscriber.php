<?php

namespace SocietoPlugin\Societo\ForumPlugin;

use Doctrine\ORM\Events;
use SocietoPlugin\Societo\ForumPlugin\Entity\ForumComment;

class UpdateForumCommentCountEventSubscriber implements \Doctrine\Common\EventSubscriber
{
    public function onFlush($e)
    {
        $em = $e->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() AS $entity) {
            $this->updateCommentCount($em, $entity);
        }
    }

    private function updateCommentCount($em, $entity)
    {
        if ($entity instanceof ForumComment) {
            $forum = $entity->getForum();
            $count = $em->getRepository('SocietoForumPlugin:ForumComment')
                ->countByForumId($forum->getId());
            $forum->setCommentCount($count + 1);

            $metadata = $em->getClassMetadata('SocietoPlugin\Societo\ForumPlugin\Entity\Forum');
            $em->persist($forum);
            $em->getUnitOfWork()->computeChangeSet($metadata, $forum);
        }
    }

    public function getSubscribedEvents()
    {
        return array(Events::onFlush);
    }
}
