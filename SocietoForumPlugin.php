<?php

namespace SocietoPlugin\Societo\ForumPlugin;

use Societo\PluginBundle\Plugin\SocietoPlugin;

class SocietoForumPlugin extends SocietoPlugin
{
    public function getAuthor()
    {
        return 'Kousuke Ebihara';
    }

    public function getVersion()
    {
        return '0.5.0';
    }

    public function boot()
    {
        $dispatcher = $this->container->get('event_dispatcher');

        $dispatcher->addListener('onSocietoRoutingParameterBuild', function($event) {
            $event->getManager()
                ->setParameter('forum')
            ;
        });

        $dispatcher->addListener('onSocietoMatchedRouteParameterFilter', function ($event) {
            $parameters = $event->getParameters();
            $em = $event->getEntityManager();

            if (isset($parameters['forum'])) {
                $parameters['forum'] = $em->getRepository('SocietoForumPlugin:Forum')->find($parameters['forum']);
            }

            $event->setParameters($parameters);
        });

        $dispatcher->addListener('onSocietoGeneratingRouteParameterFilter', function ($event) {
            $parameters = $event->getParameters();
            $em = $event->getEntityManager();

            if (isset($parameters['forum'])) {
                $parameters['forum'] = $parameters['forum']->getId();
            }

            $event->setParameters($parameters);
        });
    }
}
