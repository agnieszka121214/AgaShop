<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RegisterController extends Controller
{

    /**
     * @Route("/register_form/{msg}", name="register_form"  )
     */
    public function registerFormAction( $msg=null )
    {

        $data = array(
            'msg' => $msg
        );
        // replace this example code with whatever you need


        return $this->render('register/register.html.twig', $data);
    }

    /**
     * @Route("/register", name="register"  )
     */

    public function registerAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Account');
        $em = $this->getDoctrine()->getManager();

        //1. pobrac z okienka dane
        if(isset($_POST['register']))
        {
            $name=$_POST['name']; //pobiera z bazy i przypisuje do zmiennej $name
            $email=$_POST['email'];
            $passowrd=$_POST['password'];
            //TODO walidacja parametrów


            $account = $repository->findOneBy(
                array(
                    'login' => $name,
                    'email' => $email
                )
                //sprawdza po nazwie i mailu czy juz istnieje
            );


            if($account)
            {
                //TODO wyswietlic blad jesli istnieje
                return $this->redirectToRoute("login_form",array('msg'=>"User istnieje"));
            }
            else
            {
                $account = new Account();
                $account->setLogin($name);
                $account->setEmail($email);
                $account->setPassword($passowrd);
                $account->setIsAdmin(false);

                $em->persist($account);

                $em->flush();
                return $this->redirect("login_form"); //wykonuje akcjie login_form i zwraca to co zwraca ta akcja
            }

        }

        //1.5 sprawdzic czy istnieje




        //2.zapisywanie do bazy
        //jezeli dodano - wyswietl logowanie
        //jesli istnieje - blad ze juz istnieje



        $data = array();

        return 0;
    }
}
