<?php

namespace Admin\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Likes
 *
 * @ORM\Table(name="likes")
 * @ORM\Entity(repositoryClass="Admin\ArticleBundle\Repository\LikesRepository")
 */
class Likes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="like_dislike", type="boolean")
     */
    private $likeDislike;

    /**
     * @var string
     *
     * @ORM\Column(name="article", type="string", length=255)
     */
    private $article;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set likeDislike
     *
     * @param boolean $likeDislike
     *
     * @return Likes
     */
    public function setLikeDislike($likeDislike)
    {
        $this->likeDislike = $likeDislike;

        return $this;
    }

    /**
     * Get likeDislike
     *
     * @return bool
     */
    public function getLikeDislike()
    {
        return $this->likeDislike;
    }

    /**
     * Set article
     *
     * @param string $article
     *
     * @return Likes
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return string
     */
    public function getArticle()
    {
        return $this->article;
    }
}

