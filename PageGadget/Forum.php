<?php

namespace SocietoPlugin\Societo\ForumPlugin\PageGadget;

use \Societo\PageBundle\PageGadget\AbstractPageGadget;

/**
 *
 * @author Kousuke Ebihara <ebihara@php.net>
 */
class Forum extends AbstractPageGadget
{
    const ALL_FORUM = 0;
    const ONLY_NORMAL_FORUM = 1;
    const ONLY_GROUP_FORUM = 2;

    public $caption = 'Forum box';

    public $description = 'Show a box for displaying forum informations';

    public function execute($gadget, $parentAttributes, $parameters)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $forum = $parentAttributes->get('forum');
        $group = $parentAttributes->get('group');

        $type = $gadget->getParameter('forum_type', self::ALL_FORUM);
        if (self::ONLY_NORMAL_FORUM == $type && $forum->getGroup()) {
            throw $this->createNotFoundException('Do not use this gadget for group forum');
        } elseif (self::ONLY_GROUP_FORUM == $type) {
            if (!$forum->getGroup()) {
                throw $this->createNotFoundException('Do not use this gadget for normal forum');
            }

            if ($group->getId() != $forum->getGroup()->getId()) {
                throw $this->createNotFoundException(sprintf('Related group to this forum (id: %d) does not match with expected one by request (id: %d)', $forum->getGroup()->getId(), $group->getId()));
            }
        }

        return $this->render('SocietoForumPlugin:PageGadget:forum.html.twig', array(
            'gadget' => $gadget,
            'forum'  => $forum,
            'group'  => $group,
        ));
    }

    public function getOptions()
    {
        return array(
            'forum_type' => array(
                'type' => 'choice',
                'options' => array(
                    'choices' => array(
                        self::ALL_FORUM         => 'Show all type of forum',
                        self::ONLY_NORMAL_FORUM => 'Show only normal forum',
                        self::ONLY_GROUP_FORUM  => 'Show only group forum',
                    ),
                ),
            ),
        );
    }
}
