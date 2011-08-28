<?php

namespace SocietoPlugin\Societo\ForumPlugin\PageGadget;

use \Societo\PageBundle\PageGadget\AbstractPageGadget;

/**
 *
 * @author Kousuke Ebihara <ebihara@php.net>
 */
class CreateForumForm extends AbstractPageGadget
{
    public $caption = 'Create forum form';

    public $description = 'Show a form for creating new forum';

    public function execute($gadget, $parentAttributes, $parameters)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $user = $this->get('security.context')->getToken()->getUser();

        $forum = new \SocietoPlugin\Societo\ForumPlugin\Entity\Forum();

        if ($parameters->has('form')) {
            $form = $parameters->get('form');
        } else {
            $form = $this->get('form.factory')
                ->create(new \SocietoPlugin\Societo\ForumPlugin\Form\ForumType(), $forum);
            $form['redirect_to']->setData($gadget->getParameter('redirect_to'));
        }

        $group = $parentAttributes->get('group');
        $creatable = true;
        if ($group) {
            $repository = $em->getRepository('SocietoGroupBundle:AssociationalGroupMember');
            $creatable = $repository->isGroupMember($group->getId(), $user->getMemberId());
        }

        return $this->render('SocietoForumPlugin:PageGadget:create_forum_form.html.twig', array(
            'gadget' => $gadget,
            'form' => $form->createView(),
            'group' => $group,
            'creatable' => $creatable,
        ));
    }

    public function getOptions()
    {
        return array(
            'redirect_to' => array(
                'type' => 'text',
                'options' => array(
                    'required' => false,
                ),
            ),
        );
    }
}
