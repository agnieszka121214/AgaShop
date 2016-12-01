<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends DefaultController
{
    /**
     * @Route("/admin", name="admin"  )
     */
    public function indexAction()
    {


        return $this->render('admin/admin_panel.html.twig');
    }

    /**
     * @Route("/admin/products/{error}", name="admin_products"  )
     */
    public function productsAction( $error = null)
    {

        if(!$this->isAdminLogged())
        {
            return $this->redirectToRoute('products');
        }

        $em  = $this->getDoctrine()->getManager();
        $products = $em->createQueryBuilder()
            ->select('p', 'c')
            ->from('AppBundle:Product', 'p')
            ->leftJoin('p.category', 'c')
            ->getQuery()
            ->getResult();

        if( $error == "file_type_error" ){
            $error = "Zły format, możliwe tylko: jpg, png, jpeg, gif";
        }
        if( $error == "file_size_error" ){
            $error = "Zbyt duzy plik, maksymalny rozmiar to 2.5 MB";
        }



        $data = array(
            'products' => $products,
            'error' => $error
        );


        return $this->render('admin/admin_products.html.twig', $data);

    }
//pobieranie w linku [rzez get product_id
    /**
     * @Route("/admin/product/edit_form/{product_id}", name="admin_product_edit_form"  )
     */
    public function edit_form_productsAction($product_id)
    {

        if(!$this->isAdminLogged())
        {
            return $this->redirectToRoute('products');
        }

        //pobieramy id

        $product=$this->getDoctrine()->getRepository('AppBundle:Product')->find($product_id);


        $categories=$this->getDoctrine()->getRepository("AppBundle:Category")->findAll();

        $data = array(
            'product' => $product,
            'categories'=> $categories
        );

        return $this->render('admin/admin_product_edit.html.twig', $data);

    }

    /**
     * @Route("/admin/product/save", name="admin_product_save"  )
     */
    public function save_productsAction()
    {

        if(!$this->isAdminLogged())
        {
            return $this->redirectToRoute('products');
        }

        $name=$_POST['name'];
        $price=$_POST['price'];
        $quantity=$_POST['quantity'];
        $description=$_POST['description'];
        $image = null;
        //tworzymy sciezke do ktoej zapisuje plik

            //"http://localhost/workspace/agashop2/web/bundles/images/";

        if($_FILES["fileToUpload"]["name"]) {

            $target_dir = $_SERVER['DOCUMENT_ROOT'].'/workspace/AgaShop2/web/bundles/images/';
            $filename = basename($_FILES["fileToUpload"]["name"]);
            //nazwa pliku - sciezka pliku godzie chcemy zapisac
            $target_file = $target_dir . $filename;

            while (file_exists($target_file)) {
                $target_file = $target_dir . $this->generateRandomString(6) . $filename;
            }

            // Check file size
            if ($_FILES["fileToUpload"]["size"] > 2500000) {//2,5MB
                $error = "file_size_error";
                return $this->redirectToRoute('admin_products', array('error' => $error));
            }

            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
//Zły format, możliwe tylko: jpg, png, jpeg, gif

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif"
            ) {
                $error = "file_type_error";
                return $this->redirectToRoute('admin_products', array('error' => $error));
            }


            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
                return 0;
            }

            $image = $filename;
        }

        $category_id=$_POST['category_id'];
        $id=$_POST['id'];




        //

        $category= $this->getDoctrine()->getRepository('AppBundle:Category')->find($category_id);
        $product= $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);

        if(!$product) {
            $product= new Product();
        }


        $product->setName($name);
        $product->setPrice($price);
        $product->setQuantity($quantity);
        $product->setCategory($category);
        $product->setDescription($description);

        if($image){
            $product->setImage($image);
        }
//dodac reszte tutaj i w widoku

        $em = $this->getDoctrine()->getManager();

        $em->persist($product);
        $em->flush();
        return $this->redirectToRoute('admin_products');

    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * @Route("/admin/product/delete/{product_id}", name="admin_product_delete"  )
     */
    public function delete_productsAction($product_id)
    {

        if(!$this->isAdminLogged())
        {
            return $this->redirectToRoute('products');
        }


        $repository=$this->getDoctrine()->getRepository('AppBundle:Product');
        $product=$repository->find($product_id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('admin_products');

    }


    /**
     * @Route("/admin/product/add", name="admin_product_add"  )
     */
    public function add_productsAction()
    {

        if(!$this->isAdminLogged())
        {
            return $this->redirectToRoute('products');
        }


        $categories=$this->getDoctrine()->getRepository("AppBundle:Category")->findAll();

        $data = array(
            'product' => new Product(),
            'categories'=> $categories
        );


        return $this->render('admin/admin_product_edit.html.twig', $data);

    }

    /**
     * @Route("/admin/order", name="admin_order"  )
     */
    public function adminOrderAction()
    {

        if (!$this->isAdminLogged()) {
            return $this->redirectToRoute("login_form");
        }


        $em = $this->getDoctrine()->getManager();

        $orders = $em->createQueryBuilder()
            ->select('o', 'a')
            ->from('AppBundle:Order', 'o')
            ->leftJoin('o.account', 'a')
            ->getQuery()
            ->getResult();

        $data = array(
            'orders' => $orders,
            'account' => $this->getLoggedAccount()
        );

        return $this->render('admin/admin_orders.html.twig',$data);
    }

    /**
     * @Route("/admin/order/save/{order_id}", name="admin_order_edit"  )
     */
    public function adminOrderSaveAction($order_id)
    {
        if (!$this->isAdminLogged()) {
            return $this->redirectToRoute("login_form");
        }
        $state = $_POST['state'];
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('AppBundle:Order')->find($order_id);
        $order->setState($state);
        $em->persist($order);
        $em->flush();
        return $this->redirectToRoute('admin_order');
    }
}
