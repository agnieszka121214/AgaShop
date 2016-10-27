<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class LoginController extends DefaultController
{

    /**
     * @Route("/login_form/{msg}", name="login_form"  )
     */
    public function loginFormAction( $msg=null )
    {

        if($this->isLogged())
        {
            return $this->redirectToRoute('products');
        }

        $data = array(
            'msg' => $msg
        );

        return $this->render('login/login.html.twig', $data);

    }

    /**
     * @Route("/logout", name="logout"  )
     */
    public function logoutAction()
    {

        $this->logout();

        $data = array();
        return $this->render('login/login.html.twig', $data);
    }

    /**
     * @Route("/login", name="login"  )
     */
    public function loginAction()
    {

        $repository = $this->getDoctrine()->getRepository('AppBundle:Account');
        $em = $this->getDoctrine()->getManager();

        //1. pobiera z okienka dane
        if(isset($_POST['login']))
        {
            //pobiera z bazy i przypisuje do zmiennej $name
            $email=$_POST['email'];
            $passowrd=$_POST['password'];



            $account = $repository->findOneBy(
                array(
                    'email' => $email,
                    'password' => $passowrd
                )
            //sprawdza po nazwie i mailu czy juz istnieje
            );

            if($account)
            {
                $this->login($account); //zalogowanego uzytkownika ID
                return $this->redirectToRoute('products'); //wykonuje akcjie products i zwraca to co zwraca ta akcja
            }
            else
            {
                //TODO wyswietlic blad jesli istnieje
                //return $this->redirect( 'login_form' );
                return $this->redirectToRoute("login_form",array('msg'=>"User nie istnieje"));

            }

        }

        //1. zczytujemy parametry z $_POST[]
        //2. zapytanie do bazy czy istnieje
        //3. jak istnieje to loguje
        //4. jak nie istnieje to nieloguje

        return $this->redirectToRoute("login_form");





    }



}
