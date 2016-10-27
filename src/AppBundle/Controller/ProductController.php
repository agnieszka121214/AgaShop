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
        //$ProductRepository = new ProductRepository($em);



        //pobieranie producktow
        $sql= 'SELECT p.id as product_id , p.name , p.description , p.prise , p.image , p.category_id , c.name as category_name 
              FROM `product` AS p LEFT JOIN `category` AS C ON p.category_id = c.id ';

        if($category_id){
            $sql = $sql . " WHERE c.id =" . $category_id;
        }

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        //$categories = $ProductRepository->getProducts($category_id);
        $products = $stmt->fetchAll();

        //pobieranie category
        $sql = "SELECT * FROM `category` ";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();


        //$categories = $ProductRepository->getCategories();
        $categories = $stmt->fetchAll();


        /*$qb = $em->createQueryBuilder();
        $qb->select('p', 'c')
            ->from('AppBundle:Product', 'p')
            ->leftJoin('p.category', 'c');*/

        //$this->trace($products);

        $data = array(
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

        $sql = "SELECT * FROM `product` WHERE `id` =  " + $product_id;
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $product = $stmt->fetch();

        $data = array(
            'product' => $product,
            'account' => $this->getLoggedAccount()
        );
        return $this->render('aaaaaa', $data);
    }



}
