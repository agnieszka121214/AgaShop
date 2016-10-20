<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProductController extends DefaultController
{

    /**
     * @Route("/products", name="products"  )
     */
    public function productsAction()
    {
        /*if( !$this->isLogged() ){
            return $this->redirectToRoute("login_form");
        }
*/
        $em  = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p', 'c')
            ->from('AppBundle:Product', 'p')
            ->leftJoin('p.category', 'c');

        $data = array(
            'products' => $qb->getQuery()->getResult(),
            'account' => $this->getLoggedAccount()
        );
        // replace this example code with whatever you need


        return $this->render(':product:products.html.twig', $data);
    }


}
