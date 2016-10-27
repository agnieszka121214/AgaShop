<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    public function isLogged(){
        return isset($_SESSION['logged_account_id']);
    }

    public function login( $account ){
        $_SESSION['logged_account_id'] = $account->getId();
    }

    public function getLoggedAccount(){
        $repository = $this->getDoctrine()->getRepository('AppBundle:Account');
        return $repository->find( $_SESSION['logged_account_id'] );
    }

    public function logout(){
        $_SESSION['logged_account_id'] = null;
        session_destroy();
    }

    public function __construct(){ }

    /**
     * Override method to call #containerInitialized method when container set.
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null){
        parent::setContainer($container);
        $this->containerInitialized();
    }

    /**
     * Perform some operations after controller initialized and container set.
     */
    private function containerInitialized(){
       $this->onCreate();
    }

    public function onCreate(){
        /* */
        @session_start();
    }

    public function trace( $exp ){
        echo "<pre>";
        print_r($exp);
        echo "<pre/>";
    }

}
