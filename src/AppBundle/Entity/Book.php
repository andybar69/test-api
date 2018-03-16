<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Book
 *
 * @ORM\Table(name="book")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookRepository")
 */
class Book
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
     * @var string
     *
     * @ORM\Column(name="isbn", type="string", length=255, unique=true)
     */
    private $isbn;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publicationDate", type="datetime")
     */
    private $publicationDate;

    /**
     * @var Author []
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Author", mappedBy="books")
     */
    private $authors;

    /**
     * @var Review []
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Review", mappedBy="book")
     */
    private $reviews;


    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

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
     * Set isbn
     *
     * @param string $isbn
     *
     * @return Book
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Get isbn
     *
     * @return string
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Book
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Book
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set Author
     *
     * @param Author $author
     *
     * @return Book
     */
    public function addAuthor(Author $author)
    {
        $this->authors[] = $author;
        $author->addBook($this);
        return $this;
    }

    /**
     * @param Author $author
     *
     * @return Book
     */
    public function removeAuthor(Author $author)
    {
        if ($this->authors->contains($author)) {
            $this->authors->removeElement($author);
        }
        return $this;
    }

    /**
     * @return Author[]|ArrayCollection
     */
    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function addReview(Review $review)
    {
        $this->reviews[] = $review;

        return $this;
    }

    public function removeReview(Review $review)
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
        }

        return $this;
    }

    /**
     * @return Review[]
     */
    public function getReviews(): array
    {
        return $this->reviews;
    }

    /**
     * Set publicationDate
     *
     * @param \DateTime $publicationDate
     *
     * @return Book
     */
    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    /**
     * Get publicationDate
     *
     * @return \DateTime
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }
}

