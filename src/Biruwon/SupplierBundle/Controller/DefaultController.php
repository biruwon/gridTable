<?php

namespace Biruwon\SupplierBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SupplierBundle:Default:index.html.twig', array('name' => $name));
    }
}
