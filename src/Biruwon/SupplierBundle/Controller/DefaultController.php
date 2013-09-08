<?php

namespace Biruwon\SupplierBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query\Expr;
use Biruwon\SupplierBundle\Entity\Product;
use Biruwon\SupplierBundle\Form\CountrySelect;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $countries = $em->getRepository('SupplierBundle:Country')->findAll();

        $form = $this->createForm(new CountrySelect(), $countries);

        return $this->render('SupplierBundle:Default:index.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function productDataAction(Request $request)
    {
        if($request->isXmlHttpRequest()){

            //Check if params exists
            $rows = $request->get('rows');
            $page = $request->get('page');

            $offset = ($rows*$page) - $rows;

            $em = $this->getDoctrine()->getManager();

            $dqlQuery = 'SELECT p.name,
                            sum(oi.amount) as totalUnits,
                            sum(oi.cost) as totalCost,
                            sum(oi.amount*p.price) as totalRevenue
                        FROM SupplierBundle:OrderItem oi
                            LEFT JOIN SupplierBundle:Product p
                                WITH oi.product = p.id
                            JOIN SupplierBundle:Order o
                                WHERE oi.order = o.id
                                AND o.createdAt = CURRENT_DATE()
                        ';

            //Select country
            if($request->query->has('countryId') && $request->get('countryId')){

                $countryId = $request->get('countryId');
                $dqlQuery .= ' JOIN SupplierBundle:Store s
                                    WITH o.store = s.id
                                JOIN SupplierBundle:Country c
                                    WITH s.country = :countryId';
            }

            $dqlQuery .= ' GROUP BY p.id';

            $query = $em->createQuery($dqlQuery);

            if (isset($countryId)) {

                $query->setParameter('countryId', $countryId);
            }

            $records = count($query->getScalarResult()); //Total rows

            //Pagination
            $query->setMaxResults($rows);
            $query->setFirstResult($offset);

            $products = $query->getResult();

            $total = ceil($records/$rows); //Change page and check errors

            $productsToEncode['rows'] = array();
            $productsToEncode['page'] = $page;
            $productsToEncode['total'] = $total;
            $productsToEncode['records'] = $records;
            // $productsToEncode['lastPage'] = $total;

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
