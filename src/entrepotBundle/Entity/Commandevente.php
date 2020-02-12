<?php


namespace entrepotBundle\Entity;
Use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 *
 */
class Commandevente
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
    private $total;
    /**
     * @ORM\Column(type="date")
     */
    private $date;
    /**
     * @ORM\Column(type="string")
     */
    private $etat;
    /**
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(name="idclient",referencedColumnName="Id")
     */
    private  $idclient;
    /**
     * @ORM\ManyToOne(targetEntity="Livreur")
     * @ORM\JoinColumn(name="idlivreur",referencedColumnName="Id")
     */
    private  $idlivreur;


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
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param mixed $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
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

    /**
     * @return mixed
     */
    public function getIdlivreur()
    {
        return $this->idlivreur;
    }

    /**
     * @param mixed $idlivreur
     */
    public function setIdlivreur($idlivreur)
    {
        $this->idlivreur = $idlivreur;
    }
}