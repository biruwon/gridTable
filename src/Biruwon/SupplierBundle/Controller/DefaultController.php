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

            $dqlQuery = 'SELECT p.name,
                            sum(oi.amount) as totalUnits,
                            sum(oi.cost) as totalCost,
                            sum(oi.amount*p.price) as totalRevenue,
                            (sum(oi.cost) - sum(oi.amount*p.price)) as profit
                        FROM SupplierBundle:OrderItem oi
                            LEFT JOIN SupplierBundle:Product p
                                WITH oi.product = p.id
                            JOIN SupplierBundle:Order o
                                WHERE oi.order = o.id
                        ';

            //Select date
            if($request->query->has('date') && $request->get('date')){
                $date = $request->get('date');
                $dqlQuery .= ' AND o.createdAt = :date';
            } elseif($request->query->has('from') && $request->get('from')
                && $request->query->has('to') && $request->get('to'))
            {
                $from = $request->get('from');
                $to = $request->get('to');
                $dqlQuery .= ' AND o.createdAt BETWEEN :from AND :to';
            } else {
                $dqlQuery .= ' AND o.createdAt = CURRENT_DATE()';
            }

            //Select country
            if($request->query->has('countryId') && $request->get('countryId')){

                $countryId = $request->get('countryId');
                $dqlQuery .= ' JOIN SupplierBundle:Store s
                                    WITH o.store = s.id
                                JOIN SupplierBundle:Country c
                                    WITH s.country = :countryId';
            }

            $dqlQuery .= ' GROUP BY p.id';

            //Click order column
            if($request->query->has('sidx') && $request->get('sidx')
                && $request->query->has('sord') && $request->get('sord'))
            {
                $sortColumn = $request->get('sidx');
                $sort = $request->get('sord');
                $dqlQuery .= ' ORDER BY '.$sortColumn.' '.$sort;
            }

            $query = $em->createQuery($dqlQuery);

            if (isset($countryId)) {

                $query->setParameter('countryId', $countryId);
            }

            if (isset($date)) {

                $query->setParameter('date', $date);
            }

            if (isset($from) && isset($to) ) {

                $query->setParameter('from', $from);
                $query->setParameter('to', $to);
            }

            //Check if params exists
            $rows = $request->get('rows');
            $page = $request->get('page');

            $offset = ($rows*$page) - $rows;

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
