<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Customer
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerRepository")
 */
class Customer
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
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     * @Assert\NotBlank(
     *     message="customer.firstname.not_blank"
     * )
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     * @Assert\NotBlank(
     *     message="customer.lastname.not_blank"
     * )
     */
    private $lastname;


    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=255)
     * @Assert\NotBlank(
     *     message="customer.nickname.not_blank"
     * )
     */
    private $nickname;

    /**
     * @var string
     *
     * @ORM\Column(name="society", type="string", length=255, nullable=true)
     */
    private $society;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="zipcode", type="string", length=5, nullable=true)
     */
    private $zipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="datetime", nullable=true)
     * @Assert\Date(
     *     message="Not a date"
     * )
     */
    private $birthdate;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="User is required")
     * @Assert\Valid()
     */
    private $user;


    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Card", mappedBy="customer")
     */
    private $cards;

    /**
     * @var Image
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $avatar;

    /**
     * @var Player
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Player", mappedBy="customer")
     */
    private $customerGames;

    /**
     * @var CustomerOffer
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CustomerOffer", mappedBy="customer")
     */
    private $customerOffers;


    
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Customer
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Customer
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     *
     * @return Customer
     */
    public function setNickname($nickname)
    {
        if(empty($nickname))
        {
            throw new \InvalidArgumentException("alert.customer.nickname.required");
        }
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set society
     *
     * @param string $society
     *
     * @return Customer
     */
    public function setSociety($society)
    {
        $this->society = $society;

        return $this;
    }

    /**
     * Get society
     *
     * @return string
     */
    public function getSociety()
    {
        return $this->society;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Customer
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     *
     * @return Customer
     */
    public function setZipcode($zipcode)
    {
        if(strlen($zipcode) !== 5) {
            throw new \InvalidArgumentException('Zip code must contain 5 digits');
        }
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Customer
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return Customer
     */
    public function setBirthdate(\DateTime $birthdate=null)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Customer
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cards = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customerGames = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customerOffers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add card
     *
     * @param \AppBundle\Entity\Card $card
     *
     * @return Customer
     */
    public function addCard(\AppBundle\Entity\Card $card)
    {
        $this->cards[] = $card;

        return $this;
    }

    /**
     * Remove card
     *
     * @param \AppBundle\Entity\Card $card
     */
    public function removeCard(\AppBundle\Entity\Card $card)
    {
        $this->cards->removeElement($card);
    }

    /**
     * Get cards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * Set avatar
     *
     * @param \AppBundle\Entity\Image $avatar
     *
     * @return Customer
     */
    public function setAvatar(\AppBundle\Entity\Image $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \AppBundle\Entity\Image
     */
    public function getAvatar()
    {
        return $this->avatar;
    }


    /**
     * Add customerGame
     *
     * @param \AppBundle\Entity\Player $customerGame
     *
     * @return Customer
     */
    public function addCustomerGame(\AppBundle\Entity\Player $customerGame)
    {
        $this->customerGames[] = $customerGame;

        return $this;
    }

    /**
     * Remove customerGame
     *
     * @param \AppBundle\Entity\Player $customerGame
     */
    public function removeCustomerGame(\AppBundle\Entity\Player $customerGame)
    {
        $this->customerGames->removeElement($customerGame);
    }

    /**
     * Get customerGames
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerGames()
    {
        return $this->customerGames;
    }

    /**
     * Add customerOffer
     *
     * @param \AppBundle\Entity\CustomerOffer $customerOffer
     *
     * @return Customer
     */
    public function addCustomerOffer(\AppBundle\Entity\CustomerOffer $customerOffer)
    {
        $this->customerOffers[] = $customerOffer;

        return $this;
    }

    /**
     * Remove customerOffer
     *
     * @param \AppBundle\Entity\CustomerOffer $customerOffer
     */
    public function removeCustomerOffer(\AppBundle\Entity\CustomerOffer $customerOffer)
    {
        $this->customerOffers->removeElement($customerOffer);
    }

    /**
     * Get customerOffers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerOffers()
    {
        return $this->customerOffers;
    }
}
