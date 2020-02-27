<?php

namespace entrepotBundle\Controller;

use entrepotBundle\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ob\HighchartsBundle\Highcharts\Highchart;
use CMEN\GoogleChartsBundle\CMENGoogleChartsBundle;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use entrepotBundle\Entity\Produit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class StatController extends Controller
{
   public function StatAction(){
       $pieChart = new PieChart();
       $em= $this->getDoctrine()->getManager();
       $alp = $em->getRepository(Produit::class)->findAll();
       $alq = $em->getRepository(Categorie::class)->findAll();

       $ar = [['Categorie', 'Produit']];

       foreach ( $alq as $c)
       {
           $l = [$c->getLib()];
           $i = 0;
           foreach ($alp as $p)
           {
               if($p->getCategorie()->getId() === $c->getId())
                   $i++;
           }
           $l[]=$i;
           $ar[] =$l;

       }





       $pieChart->getData()->setArrayToDataTable(
           $ar
       );
       $pieChart->getOptions()->setTitle('Pourcentage de produits par catégorie : ');

       $pieChart->getOptions()->setHeight(1500);
       $pieChart->getOptions()->setWidth(1300);
       $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
       $pieChart->getOptions()->getTitleTextStyle()->setColor('#808080');
       $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
       $pieChart->getOptions()->getTitleTextStyle()->setFontName('serial');
       $pieChart->getOptions()->getTitleTextStyle()->setFontSize(30);

       return $this->render('entrepotBundle:Stat:Stat.html.twig', array('piechart' => $pieChart));


   }
}


