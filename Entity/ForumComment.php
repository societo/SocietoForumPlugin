<?php

namespace SocietoPlugin\Societo\ForumPlugin\Entity;

use \Societo\BaseBundle\Entity\AbstractContent;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="SocietoPlugin\Societo\ForumPlugin\Repository\ForumCommentRepository")
 * @ORM\Table(name="forum_comment")
 */
class ForumComment extends AbstractContent
{
    /**
     * @ORM\ManyToOne(targetEntity="Forum")
     * @ORM\JoinColumn(name="forum_id", referencedColumnName="id")
     */
    protected $forum;

    public function __construct($forum = null, $body = null, $author = null, $group = null)
    {
        $this->setForum($forum);
        $this->setBody($body);
        $this->setAuthor($author);
    }

    public function setForum($forum)
    {
        $this->forum = $forum;
    }

    public function getForum()
    {
        return $this->forum;
    }

    public function setTitle($forum)
    {
        $this->forum = $forum;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }
}
