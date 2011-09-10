<?php

namespace SocietoPlugin\Societo\ForumPlugin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\TemplateReference;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Societo\BaseBundle\Util\ArrayAccessibleParameterBag;

class ForumController extends Controller
{
    public function createAction($gadget, $group = null)
    {
        $request = $this->get('request');
        if ($request->getMethod() !== 'POST') {
            throw $this->createNotFoundException();
        }

        if ('SocietoForumPlugin:CreateForumForm' !== $gadget->getName()) {
            throw $this->createNotFoundException();
        }

        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->get('doctrine.orm.entity_manager');

        if ($group) {
            $repository = $em->getRepository('SocietoGroupBundle:AssociationalGroupMember');
            if (!$repository->isGroupMember($group->getId(), $user->getMemberId())) {
                throw $this->createNotFoundException();
            }
        }

        $forum = new \SocietoPlugin\Societo\ForumPlugin\Entity\Forum();
        $form = $this->get('form.factory')
            ->create(new \SocietoPlugin\Societo\ForumPlugin\Form\ForumType(), $forum);
        $form->bindRequest($request);
        if ($form->isValid()) {
            $forum = $form->getData();
            $forum->setGroup($group);
            $forum->setAuthor($user->getMember());

            $em->persist($forum);
            $em->flush();

            $uri = $form->has('redirect_to') ? $form->get('redirect_to')->getData() : '_root';
            try {
                $params = array('forum' => $forum);
                if ($group) {
                    $params['group'] = $group;
                }
                $uri = $this->generateUrl($uri, $params);
            } catch (\InvalidArgumentException $e) {
                // do nothing
            }

            $this->get('session')->setFlash('success', 'Changes are saved successfully');

            return $this->redirect($uri);
        }

        return $this->render('SocietoPluginBundle:Gadget:only_gadget.html.twig', array(
            'gadget' => $gadget,
            'parent_attributes' => $this->get('request')->attributes,
            'parameters' => new ArrayAccessibleParameterBag(array('form' => $form)),
        ));
    }

    public function addCommentAction($gadget, $forum)
    {
        $request = $this->get('request');
        if ($request->getMethod() !== 'POST') {
            throw new \Exception();
        }

        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->get('doctrine.orm.entity_manager');

        $group = $forum->getGroup();
        if ($group) {
            $repository = $em->getRepository('SocietoGroupBundle:AssociationalGroupMember');
            if (!$repository->isGroupMember($group->getId(), $user->getMemberId())) {
                throw $this->createNotFoundException();
            }
        }

        $comment = new \SocietoPlugin\Societo\ForumPlugin\Entity\ForumComment($forum);

        $form = $this->get('form.factory')
            ->create(new \SocietoPlugin\Societo\ForumPlugin\Form\ForumCommentType(), $comment);

        $form->bindRequest($request);
        if ($form->isValid()) {
            $comment = $form->getData();
            $comment->setForum($forum);
            $comment->setAuthor($user->getMember());

            $em->persist($comment);
            $em->flush();

            $uri = $form->has('redirect_to') ? $form->get('redirect_to')->getData() : '_root';
            try {
                $params = array('forum' => $forum);
                if ($group) {
                    $params['group'] = $group;
                }
                $uri = $this->generateUrl($uri, $params);
            } catch (\InvalidArgumentException $e) {
                // do nothing
            }

            $this->get('session')->setFlash('success', 'Changes are saved successfully');

            return $this->redirect($uri);
        }

        return $this->render('SocietoPluginBundle:Gadget:only_gadget.html.twig', array(
            'gadget' => $gadget,
            'parent_attributes' => $this->get('request')->attributes,
            'parameters' => new ArrayAccessibleParameterBag(array('form' => $form)),
        ));
    }
}
