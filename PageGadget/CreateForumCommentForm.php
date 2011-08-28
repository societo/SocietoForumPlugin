<?php

namespace SocietoPlugin\Societo\ForumPlugin\PageGadget;

use \Societo\PageBundle\PageGadget\AbstractPageGadget;

/**
 *
 * @author Kousuke Ebihara <ebihara@php.net>
 */
class CreateForumCommentForm extends AbstractPageGadget
{
    public $caption = 'Create forum comment form';

    public $description = 'Show a form for creating new forum comment';

    public function execute($gadget, $parentAttributes, $parameters)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $user = $this->get('security.context')->getToken()->getUser();

        $forum = $parentAttributes->get('forum');

        if ($parameters->has('form')) {
            $form = $parameters->get('form');
        } else {
            $comment = new \SocietoPlugin\Societo\ForumPlugin\Entity\ForumComment($forum);
            $form = $this->get('form.factory')
                ->create(new \SocietoPlugin\Societo\ForumPlugin\Form\ForumCommentType(), $comment);
            $form['redirect_to']->setData($gadget->getParameter('redirect_to', $this->get('request')->getRequestUri()));
        }

        $creatable = true;
        $group = $forum->getGroup();
        if ($group) {
            $repository = $em->getRepository('SocietoGroupBundle:AssociationalGroupMember');
            $creatable = $repository->isGroupMember($group->getId(), $user->getMemberId());
        }

        return $this->render('SocietoForumPlugin:PageGadget:create_forum_comment_form.html.twig', array(
            'gadget' => $gadget,
            'form'   => $form->createView(),
            'forum'  => $forum,
            'creatable' => $creatable,
        ));
    }
}
