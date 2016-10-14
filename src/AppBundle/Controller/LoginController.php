<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class LoginController extends Controller
{

    /**
     * @Route("/login_form/{msg}", name="login_form"  )
     */
    public function loginFormAction( $msg=null )
    {

        $data = array(
            'msg' => $msg
        );

        return $this->render('login/login.html.twig', $data);
    }

    /**
     * @Route("/login", name="login"  )
     */
    public function loginAction()
    {


        //1. zczytujemy parametry z $_POST[]
        //2. zapytanie do bazy czy istnieje
        //3. jak istnieje to loguje
        //4. jak nie istnieje to nieloguje

        $repository = $this->getDoctrine()->getRepository('AppBundle:Account');

        $accounts = $repository->findAll();
        if($accounts){
            $_SESSION['id'] = $accounts[0]->getId();
            //return $this->redirect( 'shop' );
        }else{
            //return $this->redirect( 'login_form' );
        }

        foreach ($accounts as $account) {
            echo "<pre>";
            print_r( $_POST['email'] );
            echo "</pre>";
        }

        // replace this example code with whatever you need
        if( isset( $_POST['email'] ) ){
            echo "<pre>";
            print_r( $_POST['email'] );
            $email = $_POST['email'];
            echo "</pre>";
        }

        $data = array();

        return $this->redirect( 'login_form' );

    }



}
