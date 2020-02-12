<?php


namespace entrepotBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
Use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 *
 */
class Produit
{
    /**
     * @var int
     * @ORM\Column(name="Id",type="integer")
     *@ORM\Id
     *@ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $lib;
    /**
     * @ORM\Column(type="float")
     */
    private $prixvente;
    /**
     * @ORM\Column(type="float")
     */
    private $prixachat;
    /**
     * @ORM\Column(type="string")
     */
    private $disponibilite;
    /**
     * @ORM\Column(type="string")
     */
    private $description;
    /**
     * @ORM\Column(type="string")
     */
    private $image;
    /**
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumn(name="idcategorie",referencedColumnName="Id")
     */
    private  $idcategorie;
    /**
     * @ORM\ManyToMany(targetEntity="fournisseur", inversedBy="produits", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="fournir",
     *     joinColumns={
     *          @ORM\JoinColumn(name="id_produit", referencedColumnName="Id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="id_fournisseur", referencedColumnName="Id")
     *     }
     * )
     */
    private  $fournisseurs;

    /**
     * @return ArrayCollection
     */
    public function getFournisseurs()
    {
        return $this->fournisseurs;
    }

    /**
     * @param ArrayCollection $fournisseurs
     */
    public function setFournisseurs($fournisseurs)
    {
        $this->fournisseurs = $fournisseurs;
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
    public function getLib()
    {
        return $this->lib;
    }

    /**
     * @param mixed $lib
     */
    public function setLib($lib)
    {
        $this->lib = $lib;
    }

    /**
     * @return mixed
     */
    public function getPrixvente()
    {
        return $this->prixvente;
    }

    /**
     * @param mixed $prixvente
     */
    public function setPrixvente($prixvente)
    {
        $this->prixvente = $prixvente;
    }

    /**
     * @return mixed
     */
    public function getPrixachat()
    {
        return $this->prixachat;
    }

    /**
     * @param mixed $prixachat
     */
    public function setPrixachat($prixachat)
    {
        $this->prixachat = $prixachat;
    }

    /**
     * @return mixed
     */
    public function getDisponibilite()
    {
        return $this->disponibilite;
    }

    /**
     * @param mixed $disponibilite
     */
    public function setDisponibilite($disponibilite)
    {
        $this->disponibilite = $disponibilite;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getIdcategorie()
    {
        return $this->idcategorie;
    }

    /**
     * @param mixed $idcategorie
     */
    public function setIdcategorie($idcategorie)
    {
        $this->idcategorie = $idcategorie;
    }

    public function __construct()
    {
        $this->fournisseurs = new ArrayCollection();
    }

}