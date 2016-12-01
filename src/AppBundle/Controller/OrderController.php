<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\OrderProduct;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

define('STATE_WATTING', 1);
define('STATE_SEND', 2);
define('STATE_COMPLETE', 3);

class OrderController extends DefaultController
{


    /**
     * @Route("/order", name="order"  )
     */
    public function orderAction()
    {
        if (!$this->isLogged()) {
            return $this->redirectToRoute("login_form");
        }

        $account = $this->getLoggedAccount();

        $em = $this->getDoctrine()->getManager();

        //pobieranie category

        $cartItems = $em->createQueryBuilder()
            ->select('c', 'p')
            ->from('AppBundle:CartItem', 'c')
            ->leftJoin('c.product', 'p')
            ->where('c.accountId = :account_id')
            ->setParameter('account_id', $account->getId())
            ->getQuery()
            ->getResult();

        $total_price=0;
        $total_quantity=0;

        foreach( $cartItems as $cartItem ){
            $total_quantity+=$cartItem->getQuantity();
            $total_price+=$cartItem->getProduct()->getPrice()*$cartItem->getQuantity();
        }

        $data = array(

            'total_price' => $total_price,
            'total_quantity' => $total_quantity,
            'cartItems' => $cartItems,
            'account' => $this->getLoggedAccount()
        );
        return $this->render('order/order.html.twig', $data);
    }

    /**
     * @Route("/order/checkout", name="order_checkout"  )
     */
    public function orderCheckoutAction()
    {

        if (!$this->isLogged()) {
            return $this->redirectToRoute("login_form");
        }

        $account = $this->getLoggedAccount();

        $em = $this->getDoctrine()->getManager();

        //pobieranie category

        $cartItems = $em->createQueryBuilder()
            ->select('c', 'p')
            ->from('AppBundle:CartItem', 'c')
            ->leftJoin('c.product', 'p')
            ->where('c.accountId = :account_id')
            ->setParameter('account_id', $account->getId())
            ->getQuery()
            ->getResult();

        $total_price=0;
        $total_quantity=0;

        foreach( $cartItems as $cartItem ){
            $total_quantity+=$cartItem->getQuantity();
            $total_price+=$cartItem->getProduct()->getPrice()*$cartItem->getQuantity();
        }


        $name=$_POST['name'];
        $city=$_POST['city'];
        $post_code=$_POST['post_code'];
        $street=$_POST['street'];

        // TODO walidacja??????????????????????????????




        $order = new Order();
        $order->setName($name);
        $order->setCity($city);
        $order->setPostCode($post_code);
        $order->setStreet($street);
        $order->setTotalPrice($total_price);
        $order->setState(STATE_WATTING);
        $order->setAccount($account);
        $order->setOrderDate(new \DateTime('NOW'));
        $em->persist($order);

        foreach( $cartItems as $c ){
            $productOrder = new OrderProduct();
            $productOrder->setName($c->getProduct()->getName());
            $productOrder->setPrice($c->getProduct()->getPrice());
            $productOrder->setQuantity($c->getQuantity());
            $productOrder->setOrder($order);
            $em->persist($productOrder);
            $em->remove($c);

        }



        $em->flush();




        return $this->redirectToRoute('my_order');

    }

    /**
     * @Route("/my_order", name="my_order"  )
     */
    public function myOrdersAction()
    {

        if (!$this->isLogged()) {
            return $this->redirectToRoute("login_form");
        }

        $account = $this->getLoggedAccount();

        $em = $this->getDoctrine()->getManager();

        $orders = $em->createQueryBuilder()
            ->select('o', 'a')
            ->from('AppBundle:Order', 'o')
            ->leftJoin('o.account', 'a')
            ->where('o.accountId = :account_id')
            ->setParameter('account_id', $account->getId())
            ->getQuery()
            ->getResult();

        $data = array(
            'orders' => $orders,
            'account' => $this->getLoggedAccount()
        );
        return $this->render('order/my_order.html.twig', $data);
    }


}
