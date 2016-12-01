<?php
/**
 * Created by PhpStorm.
 * User: Agnieszka
 * Date: 02.11.2016
 * Time: 18:26
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class User
{
    /**
     * @Assert\NotBlank( message= "AAAAAAaaaaaaaaaa" )
     */
    public $u_name;
    // * @Assert\MinLength(3)
    //  * @Assert\MinLength(6)


    /**
     * @Assert\IsTrue( message = "Hasło nie może być takie samo jak Twoje imię")
     */
    public function isPasswordLegal()
    {
        return ($this->u_name != $this->u_password);
    }
    public $u_password;

    /**
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    public $u_email;




}