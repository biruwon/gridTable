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

        $dates = $em->getRepository('SupplierBundle:Order')->getMAXandMIXDates();

        return $this->render('SupplierBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
            'dates' => $dates
        ));
    }

    public function productDataAction(Request $request)
    {
        if($request->isXmlHttpRequest()){

            $em = $this->getDoctrine()->getManager();

            $parameters = $request->query->all();

            $query = $em->getRepository('SupplierBundle:OrderItem')->getQueryGrid($parameters);

            //Check if params exists
            $rows = $request->get('rows');
            $page = $request->get('page');

            $offset = ($rows*$page) - $rows;

            $records = count($query->getScalarResult()); //Total rows

            //Pagination
            $query->setMaxResults($rows);
            $query->setFirstResult($offset);

            $products = $query->getResult();

            $total = ceil($records/$rows);

            $productsToEncode['rows'] = array();
            $productsToEncode['page'] = $page;
            $productsToEncode['total'] = $total;
            $productsToEncode['records'] = $records;
            // $productsToEncode['lastPage'] = $total;

            foreach($products as $key => $product){
                $child['id'] = $key;
                $child['p.name'] = $product['name'];
                $child['totalUnits'] = $product['totalUnits'];
                $child['totalCost'] = $product['totalCost'];
                $child['totalRevenue'] = $product['totalRevenue'];
                $child['profit'] = $product['profit'];

                array_push($productsToEncode['rows'], $child);
            }

            $response = new JsonResponse();
            $response->setData($productsToEncode);
        }

        return $response;
    }
}
