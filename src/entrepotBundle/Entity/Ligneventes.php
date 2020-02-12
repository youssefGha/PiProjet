<?php


namespace entrepotBundle\Entity;

Use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 *
 */
class Ligneventes
{
    /**
     * @var int
     * @ORM\Column(name="Id",type="integer")
     *@ORM\Id
     *@ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="float")
     */
    private $pu;
    /**
     * @ORM\Column(type="integer")
     */
    private $qt;
    /**
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumn(name="idproduit",referencedColumnName="Id")
     */
    private  $idproduit;
    /**
     * @ORM\ManyToOne(targetEntity="Commandevente")
     * @ORM\JoinColumn(name="idcommande",referencedColumnName="Id")
     */
    private  $idcommande;

    /**
     * @return mixed
     */
    public function getIdcommande()
    {
        return $this->idcommande;
    }

    /**
     * @param mixed $idcommande
     */
    public function setIdcommande($idcommande)
    {
        $this->idcommande = $idcommande;
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
    public function getPu()
    {
        return $this->pu;
    }

    /**
     * @param mixed $pu
     */
    public function setPu($pu)
    {
        $this->pu = $pu;
    }

    /**
     * @return mixed
     */
    public function getQt()
    {
        return $this->qt;
    }

    /**
     * @param mixed $qt
     */
    public function setQt($qt)
    {
        $this->qt = $qt;
    }

    /**
     * @return mixed
     */
    public function getIdproduit()
    {
        return $this->idproduit;
    }

    /**
     * @param mixed $idproduit
     */
    public function setIdproduit($idproduit)
    {
        $this->idproduit = $idproduit;
    }

    /**
     * @return mixed
     */
    public function getIdclient()
    {
        return $this->idclient;
    }

    /**
     * @param mixed $idclient
     */
    public function setIdclient($idclient)
    {
        $this->idclient = $idclient;
    }


}