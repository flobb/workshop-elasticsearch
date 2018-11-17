<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 */
class News
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $text;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $published;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $readTime;

    /**
     * @var Category[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="news")
     */
    private $categories;

    /**
     * @var NewsComment[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\NewsComment", mappedBy="news")
     */
    private $comments;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="news")
     */
    private $author;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function isPublished(): bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): void
    {
        $this->published = $published;
    }

    public function getReadTime(): int
    {
        return $this->readTime;
    }

    public function setReadTime(int $readTime): void
    {
        $this->readTime = $readTime;
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function addCategory(Category $category): void
    {
        if ($this->categories->contains($category)) {
            return;
        }

        $this->categories->add($category);
        $category->addNews($this);
    }

    public function removeCategory(Category $category): void
    {
        if (!$this->categories->contains($category)) {
            return;
        }

        $this->categories->removeElement($category);
        $category->removeNews($this);
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function addComment(NewsComment $comment): void
    {
        if ($this->comments->contains($comment)) {
            return;
        }

        $this->comments->add($comment);
        $comment->setNews($this);
    }

    public function removeComment(NewsComment $comment): void
    {
        if (!$this->comments->contains($comment)) {
            return;
        }

        $this->comments->removeElement($comment);
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }
}
