<?php

namespace Biruwon\SupplierBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Biruwon\SupplierBundle\Entity\Product;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	// $repository = $this->getDoctrine()
    	// 	->getRepository('SupplierBundle:Product');

    	// $query = $repository->createQueryBuilder('p')
    	//     ->getQuery();

    	// $dql = $query->getDql();

    	// $query = $em->createQuery($dql)
    	//                        ->setFirstResult(1)
    	//                        ->setMaxResults(5);

    	// $paginator = new Paginator($query, $fetchJoinCollection = true);

    	// var_dump(count($paginator));
    	// die();
    	$products = $em->getRepository('SupplierBundle:Product')->findAll();

        return $this->render('SupplierBundle:Default:index.html.twig', array(
        	'products' => $products
        	)
        );
    }
}
