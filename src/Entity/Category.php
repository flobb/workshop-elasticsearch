<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 */
class Category
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
    private $name;

    /**
     * @var News[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\News", mappedBy="categories")
     */
    private $news;

    public function __construct()
    {
        $this->news = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getNews()
    {
        return $this->news;
    }

    public function addNews(News $news): void
    {
        if ($this->news->contains($news)) {
            return;
        }

        $this->news->add($news);
        $news->addCategory($this);
    }

    public function removeNews(News $news): void
    {
        if (!$this->news->contains($news)) {
            return;
        }

        $this->news->removeElement($news);
        $news->removeCategory($this);
    }
}
