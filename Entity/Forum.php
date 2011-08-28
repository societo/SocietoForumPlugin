<?php

namespace SocietoPlugin\Societo\ForumPlugin\Entity;

use \Societo\BaseBundle\Entity\AbstractContent;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="SocietoPlugin\Societo\ForumPlugin\Repository\ForumRepository")
 * @ORM\Table(name="forum")
 */
class Forum extends AbstractContent
{
    /**
     * @ORM\Column(name="title", type="text")
     */
    protected $title;

    /**
     * @ORM\ManyToOne(targetEntity="Societo\GroupBundle\Entity\AssociationalGroup")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=true)
     */
    protected $group = null;

    /**
     * @ORM\Column(name="comment_count", type="integer")
     */
    protected $commentCount = 0;

    public function __construct($title = null, $body = null, $author = null, $group = null)
    {
        $this->setTitle($title);
        $this->setBody($body);
        $this->setAuthor($author);
        $this->setGroup($group);
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function setGroup($group)
    {
        $this->group = $group;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function setCommentCount($count)
    {
        $this->commentCount = $count;
    }

    public function getCommentCount()
    {
        return $this->commentCount;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
