<?php

namespace AppBundle\Controller;

use AppBundle\data\ProductRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProductController extends DefaultController
{

    /**
     * @Route("/products/{category_id}", name="products"  )
     */
    public function productsAction( $category_id = null )
    {
        if( !$this->isLogged() ){
            return $this->redirectToRoute("login_form");
        }

        $em  = $this->getDoctrine()->getManager();

        //pobieranie category

        $categories = $this->getDoctrine()
            ->getRepository('AppBundle:Category')
            ->findAll();

        if($category_id)
        {$products = $em->createQueryBuilder()
            ->select('p', 'c')
            ->from('AppBundle:Product', 'p')
            ->leftJoin('p.category', 'c')
            ->where('c.id = :category_id')
            ->setParameter('category_id', $category_id)
            ->getQuery()
            ->getResult();}
        else
        {$products = $em->createQueryBuilder()
            ->select('p', 'c')
            ->from('AppBundle:Product', 'p')
            ->leftJoin('p.category', 'c')
            ->getQuery()
            ->getResult();}



        $data = array(
            'cart_size' => $this->getCartSize(),
            'categories' => $categories,
            'products' => $products,
            'account' => $this->getLoggedAccount(),
            'category_id' => $category_id
        );
        // replace this example code with whatever you need


        return $this->render(':product:products.html.twig', $data);
    }

    /**
     * @Route("/product/{product_id}", name="product"  )
     */
    public function productAction( $product_id )
    {
        if( !$this->isLogged() ){
            return $this->redirectToRoute("login_form");
        }

        $em  = $this->getDoctrine()->getManager();

        $product=$this->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->find($product_id);

        $data = array(
            'product' => $product,
            'account' => $this->getLoggedAccount()
        );
        return $this->render('product/product.html.twig', $data);
    }


}
