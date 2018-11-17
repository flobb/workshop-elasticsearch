<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 */
class User
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
    private $email;

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
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $job;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $company;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $birthAt;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $description;

    /**
     * @var News[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\News", mappedBy="author")
     */
    private $news;

    /**
     * @var NewsComment[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\NewsComment", mappedBy="author")
     */
    private $newsComments;

    public function __construct()
    {
        $this->news = new ArrayCollection();
        $this->newsComments = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title.' '.$this->firstName.' '.$this->lastName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getJob(): string
    {
        return $this->job;
    }

    public function setJob(string $job): void
    {
        $this->job = $job;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function setCompany(string $company): void
    {
        $this->company = $company;
    }

    public function getBirthAt(): \DateTime
    {
        return $this->birthAt;
    }

    public function setBirthAt(\DateTime $birthAt): void
    {
        $this->birthAt = $birthAt;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
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
        $news->setAuthor($this);
    }

    public function removeNews(News $news): void
    {
        if (!$this->news->contains($news)) {
            return;
        }

        $this->news->remove($news);
    }

    public function getNewsComments()
    {
        return $this->newsComments;
    }

    public function addNewsComment(NewsComment $newsComment): void
    {
        if ($this->newsComments->contains($newsComment)) {
            return;
        }

        $this->newsComments->add($newsComment);
        $newsComment->setAuthor($this);
    }

    public function removeNewsComment(NewsComment $newsComment): void
    {
        if (!$this->newsComments->contains($newsComment)) {
            return;
        }

        $this->newsComments->removeElement($newsComment);
        $newsComment->setAuthor($this);
    }
}
