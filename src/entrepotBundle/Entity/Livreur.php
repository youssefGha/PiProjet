<?php


namespace entrepotBundle\Entity;

Use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
/**
 * @ORM\Entity
 *
 */
class Livreur
{
    /**
     * @var int
     * @ORM\Column(name="Id",type="integer")
     *@ORM\Id
     *@ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    private $matriculevoiture;
    /**
     * @ORM\Column(type="string")
     */
    private $cartegrise;
    /**
     * @ORM\Column(type="string")
     */
    private $imagevoiture;
    /**
     * @ORM\Column(type="string")
     */
    private $numtel;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="iduser",referencedColumnName="id")
     */
    private  $iduser;

    /**
     * @return mixed
     */
    public function getIduser()
    {
        return $this->iduser;
    }

    /**
     * @param mixed $iduser
     */
    public function setIduser($iduser)
    {
        $this->iduser = $iduser;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCin()
    {
        return $this->cin;
    }

    /**
     * @param mixed $cin
     */
    public function setCin($cin)
    {
        $this->cin = $cin;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getMatriculevoiture()
    {
        return $this->matriculevoiture;
    }

    /**
     * @param mixed $matriculevoiture
     */
    public function setMatriculevoiture($matriculevoiture)
    {
        $this->matriculevoiture = $matriculevoiture;
    }

    /**
     * @return mixed
     */
    public function getCartegrise()
    {
        return $this->cartegrise;
    }

    /**
     * @param mixed $cartegrise
     */
    public function setCartegrise($cartegrise)
    {
        $this->cartegrise = $cartegrise;
    }

    /**
     * @return mixed
     */
    public function getImagevoiture()
    {
        return $this->imagevoiture;
    }

    /**
     * @param mixed $imagevoiture
     */
    public function setImagevoiture($imagevoiture)
    {
        $this->imagevoiture = $imagevoiture;
    }

    /**
     * @return mixed
     */
    public function getNumtel()
    {
        return $this->numtel;
    }

    /**
     * @param mixed $numtel
     */
    public function setNumtel($numtel)
    {
        $this->numtel = $numtel;
    }

    /**
     * @return mixed
     */
    public function getCapacite()
    {
        return $this->capacite;
    }

    /**
     * @param mixed $capacite
     */
    public function setCapacite($capacite)
    {
        $this->capacite = $capacite;
    }

}