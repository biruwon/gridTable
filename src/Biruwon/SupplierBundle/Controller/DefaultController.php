<?php

namespace Biruwon\SupplierBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Biruwon\SupplierBundle\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    // public function indexAction()
    // {
    // 	$em = $this->getDoctrine()->getManager();

    // 	$repository = $em->getRepository('SupplierBundle:Product');

    // 	$query = $repository->createQueryBuilder('p')
    //         ->setFirstResult(1)
    //         ->setMaxResults(10)
    // 	    ->getQuery();

    //     $products = $query->getResult();

    // 	//$products = $em->getRepository('SupplierBundle:Product')->findAll();

    //     return $this->render('SupplierBundle:Default:index.html.twig', array(
    //     	'products' => $products
    //     	)
    //     );
    // }

    public function productDataAction(Request $request)
    {
        if($request->isXmlHttpRequest()){

            $em = $this->getDoctrine()->getManager();

            $query = $em->createQuery(
                'SELECT p.name, sum(o.amount) as totalUnits, sum(o.cost) as totalCost,
                sum(o.amount*p.price) as totalRevenue
                  FROM SupplierBundle:Product p
                  LEFT JOIN SupplierBundle:OrderItem o
                  WHERE p.id = o.product
                  GROUP BY p.id
                  '
            );

            $query->setMaxResults(5);
            $query->setFirstResult(0);

            $products = $query->getResult();

            $allProducts = $em->getRepository('SupplierBundle:Product')->findAll();
            $records = count($allProducts);
            $total = ceil($records/5); //Change page and check errors

            $productsToEncode['rows'] = array();
            $productsToEncode['page'] = 1;
            $productsToEncode['total'] = $total;
            $productsToEncode['records'] = $records;

            foreach($products as $key => $product){
                $child['id'] = $key;
                $child['name'] = $product['name'];
                $child['totalUnits'] = $product['totalUnits'];
                $child['totalCost'] = $product['totalCost'];
                $child['totalRevenue'] = $product['totalRevenue'];
                $child['profit'] = $product['totalRevenue'] - $product['totalCost'];

                array_push($productsToEncode['rows'], $child);
            }

            $response = new JsonResponse();
            $response->setData($productsToEncode);
        }

        return $response;
    }
}
