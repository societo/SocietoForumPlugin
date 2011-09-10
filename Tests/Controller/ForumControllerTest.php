<?php

/**
 * This file is applied CC0 <http://creativecommons.org/publicdomain/zero/1.0/>
 */

namespace SocietoPlugin\Societo\ForumPlugin\Tests\Controller;

use Societo\BaseBundle\Test\WebTestCase;

class ForumControllerTest extends WebTestCase
{
    public $em, $token;

    public function setUp()
    {
        parent::setUp();

        $this->loadFixtures(array(
            'Societo\BaseBundle\Tests\Fixtures\LoadAccountData',
            'SocietoPlugin\Societo\ForumPlugin\Tests\Fixtures\LoadForumGadgetData',
        ));
    }

    public function testGetCreateAction()
    {
        $client = $this->init();

        $client->request('GET', '/forum/create/1');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testCreateActionToInvalidGadget()
    {
        $client = $this->init();
        $client->request('POST', '/forum/create/2');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testCreateActionCsrf()
    {
        $client = $this->init();

        $crawler = $client->request('POST', '/forum/create/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegExp('/The CSRF token is invalid. Please try to resubmit the form/', $client->getResponse()->getContent());
        $this->assertEquals(0, $this->getNumberOfForum());
    }

    public function init()
    {
        $client = static::createClient();
        $this->em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $account = $this->em->find('SocietoAuthenticationBundle:Account', 1);
        $this->login($client, $account);

        $crawler = $client->request('POST', '/forum/create/1');
        $this->token = $crawler->filter('#societo_forum_forum__token')->attr('value');

        return $client;
    }

    private function getNumberOfForum()
    {
        return count($this->em->getRepository('SocietoForumPlugin:Forum')->findAll());
    }
}
