<?php


namespace entrepotBundle\Entity;

Use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="entrepotBundle\Repository\LivreurRepository")
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
     * @Assert\NotBlank(message="ne doit pas etre vide")
     * @Assert\Regex(
     *     pattern="/^(1|2|3|4|5|6|7|8|9)(0|1|2|3|4|5|6|7|8|9)(0|1|2|3|4|5|6|7|8|9)TUN(0|1|2|3|4|5|6|7|8|9)(0|1|2|3|4|5|6|7|8|9)(0|1|2|3|4|5|6|7|8|9)(0|1|2|3|4|5|6|7|8|9)$/",
     *     match=true,
     *     message="Your name cannot contain a number"
     * )
     */
    private $matriculevoiture;
    /**
     * @ORM\Column(type="string")
     */
    private $nom;
    /**
     * @ORM\Column(type="string")
     */
    private $prenom;

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
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }


    /**
 * @ORM\Column(type="string")
 */
    private $adresse;
    /**
     * @ORM\Column(type="string")
     *
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "8 chifre",
     *      maxMessage = "8chifre",
     * )
     */
    private $cin;
    /**
     * @ORM\Column(type="float",nullable= true)
     */
    private $rating;

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
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
    public function getEntretien()
    {
        return $this->entretien;
    }

    /**
     * @param mixed $entretien
     */
    public function setEntretien($entretien)
    {
        $this->entretien = $entretien;
    }

    /**
     * @return mixed
     */
    public function getAssurance()
    {
        return $this->assurance;
    }

    /**
     * @param mixed $assurance
     */
    public function setAssurance($assurance)
    {
        $this->assurance = $assurance;
    }
    /**
     * @ORM\Column(type="date")
     */
    private $entretien;
    /**
     * @ORM\Column(type="date")
     */
    private $assurance;


    /**
     * @var string
     * @Assert\File(mimeTypes={ "image/jpeg" , "image/png" , "image/tiff" , "image/svg+xml"})
     * @Assert\NotBlank(message="plz enter an image")
     * @Assert\Image()
     * @ORM\Column(name="image",type="string",length=255,nullable=true)
     */
    private  $imagev;
    /**
     * @ORM\Column(type="string")
     *
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "8 chifre",
     *      maxMessage = "8chifre",
     * )
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
     * @return string
     */
    public function getImagev()
    {
        return $this->imagev;
    }

    /**
     * @param string $imagev
     */
    public function setImagev($imagev)
    {
        $this->imagev = $imagev;
    }



}