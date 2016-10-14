<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SaleController extends Controller
{

    /**
     * @Route("/products", name="products"  )
     */
    public function productFormAction()
    {

        $repository = $this->getDoctrine()->getRepository('AppBundle:Product');
        $em = $this->getDoctrine()->getManager();

        $p= $repository->findAll();

        $data = array(
            'products' => $p
        );
        // replace this example code with whatever you need


        return $this->render(':sale:sale.html.twig', $data);
    }


}
