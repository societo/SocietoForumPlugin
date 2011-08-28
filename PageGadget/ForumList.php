<?php

namespace SocietoPlugin\Societo\ForumPlugin\PageGadget;

use Societo\PageBundle\PageGadget\AbstractPageGadget;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;

/**
 *
 * @author Kousuke Ebihara <ebihara@php.net>
 */
class ForumList extends AbstractPageGadget
{
    public $caption = 'List of forums';

    public $description = 'Show a box of list of forums';

    public function execute($gadget, $parentAttributes, $parameters)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $maxResults = $gadget->getParameter('max_results', 20);

        $groupId = null;
        $group = $parentAttributes->get('group');
        if ($group) {
            $groupId = $group->getId();
        }
        $builder = $em->getRepository('SocietoForumPlugin:Forum')->getForumQuery($groupId);
        $adapter = new DoctrineORMAdapter($builder->getQuery());
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta
            ->setMaxPerPage($maxResults)
            ->setCurrentPage($this->get('request')->query->get('page', 1))
        ;

        return $this->render('SocietoForumPlugin:PageGadget:forum_list.html.twig', array(
            'gadget' => $gadget,
            'forums' => $pagerfanta->getCurrentPageResults(),
            'group'  => $group,

            'route_to_more_page' => $gadget->getParameter('route_to_more_page'),
            'has_pager' => $gadget->getParameter('has_pager'),
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

            'route_to_more_page' => array(
                'type' => 'text',
                'options' => array(
                    'required' => false,
                ),
            ),

            'has_pager' => array(
                'type' => 'choice',
                'options' => array(
                    'choices' => array(
                        0 => 'No',
                        1 => 'Yes',
                    ),
                ),
            ),
        );
    }
}
