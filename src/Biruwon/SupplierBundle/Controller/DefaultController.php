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

    	$repository = $em->getRepository('SupplierBundle:Product');

    	$query = $repository->createQueryBuilder('p')
            ->setFirstResult(1)
            ->setMaxResults(10)
    	    ->getQuery();

        $products = $query->getResult();

    	//$products = $em->getRepository('SupplierBundle:Product')->findAll();

        return $this->render('SupplierBundle:Default:index.html.twig', array(
        	'products' => $products
        	)
        );
    }
}
