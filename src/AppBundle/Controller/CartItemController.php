<?php

namespace AppBundle\Controller;
use AppBundle\Entity\CartItem;
use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CartItemController extends DefaultController
{

    /**
     * @Route("/cart", name="cart"  )
     */
    public function cartItemAction()
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


        $data = array(

            'cartItems' => $cartItems,
            'account' => $this->getLoggedAccount()
        );
        // replace this example code with whatever you need


        return $this->render(':cart:cart_items.html.twig', $data);
    }

    /**
     * @Route("/cart/delete/{cart_item_id}", name="delete_cart_item"  )
     */
    public function deleteCartItemAction($cart_item_id)
    {

        if ($this->isAdminLogged()) {
            return $this->redirectToRoute('products');
        }


        $repository = $this->getDoctrine()->getRepository('AppBundle:CartItem');
        $cart_item = $repository->find($cart_item_id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($cart_item);
        $em->flush();

        return $this->redirectToRoute('cart');

    }

    /**
     * @Route("/cart/set_quantity/{cart_item_id}/{quantity}", name="set_cart_item_size"  )
     */
    public function setCardItemSizeAction($cart_item_id, $quantity)
    {

        if ($this->isAdminLogged()) {
            return $this->redirectToRoute('products');
        }


        $repository = $this->getDoctrine()->getRepository('AppBundle:CartItem');
        $cart_item = $repository->find($cart_item_id);
        $em = $this->getDoctrine()->getManager();

        $cart_item->setQuantity($quantity);
        if ($quantity == 0) {
            $em->remove($cart_item);
        } else {
            $em->persist($cart_item);
        }

        $em->flush();

        return $this->redirectToRoute('cart');

    }
    /**
     * @Route("/cart/add/{product_id}", name="add_cart_item"  )
     */

    //cale zle

    public function addCartItemAction($product_id)
    {

        if (!$this->isLogged()) {
            return $this->redirectToRoute("login_form");
        }

        $product = $this->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->find($product_id);

        $em = $this->getDoctrine()->getManager();
        $account = $this->getLoggedAccount();

        $item = $em->createQueryBuilder()
            ->select('a')
            ->from('AppBundle:CartItem', 'a')
            ->where('a.accountId =' . $account->getId())
            ->andwhere('a.productId =' . $product->getId())
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();

        if (!$item) {
            $item = new CartItem();
            $item->setProduct($product);
            $item->setQuantity(1);
            $item->setAccount($account);
        } else {
            $item->setQuantity( $item->getQuantity() + 1 );
        }

        $em->persist($item);
        $em->flush();

        return $this->redirectToRoute('cart');
    }
}
