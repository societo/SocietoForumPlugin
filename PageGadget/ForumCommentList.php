<?php

namespace SocietoPlugin\Societo\ForumPlugin\PageGadget;

use Societo\PageBundle\PageGadget\AbstractPageGadget;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;

/**
 *
 * @author Kousuke Ebihara <ebihara@php.net>
 */
class ForumCommentList extends AbstractPageGadget
{
    public $caption = 'List of forum comments';

    public $description = 'Show a box of list of forum comments';

    public function execute($gadget, $parentAttributes, $parameters)
    {
        $forum = $parentAttributes->get('forum');
        $maxResults = $gadget->getParameter('max_results', 20);

        $em = $this->get('doctrine.orm.entity_manager');
        $builder = $em->getRepository('SocietoForumPlugin:ForumComment')->getAllByForumIdQuery($forum->getId());
        $adapter = new DoctrineORMAdapter($builder->getQuery());
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta
            ->setMaxPerPage($maxResults)
            ->setCurrentPage($this->get('request')->query->get('page', 1))
        ;

        return $this->render('SocietoForumPlugin:PageGadget:forum_comment_list.html.twig', array(
            'gadget' => $gadget,
            'comments'  => $pagerfanta->getCurrentPageResults(),
            'pagerfanta' => $pagerfanta,
            'attributes' => $parentAttributes,
        ));
    }

    public function getOptions()
    {
        return array(
            'max_results' => array(
                'type' => 'text',
                'options' => array(
                    'required' => false,
                ),
            ),
        );
    }
}
