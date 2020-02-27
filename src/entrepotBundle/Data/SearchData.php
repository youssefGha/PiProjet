<?php


namespace entrepotBundle\Data;


use entrepotBundle\Entity\Categorie;
use Symfony\Component\Validator\Constraints as Assert;

class SearchData
{
    /**
     * @var string
     */
    public $q='';

    /**
     * @var Categorie
     */
    public $categories;

    /**
     * @var null|integer
     * @Assert\GreaterThan(0,message="valeure négative")
     */
    public $max;

    /**
     * @var null|integer
     */
    public $min;
}