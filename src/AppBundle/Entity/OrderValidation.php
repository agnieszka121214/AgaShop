<?php
/**
 * Created by PhpStorm.
 * User: Agnieszka
 * Date: 01.12.2016
 * Time: 20:26
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class OrderValidation
{
    /**
     * @Assert\NotBlank( message= "Uzupełnij wszystkie pola" )
     */
    public $name;
    public $street;
    public $city;
    public $post_code;
    // * @Assert\MinLength(3)





    ///**
    // * Assert\Regex(
    // *      message = "Zly kod pocztowy.",
    // *    pattern     = "\d{2}-\d{3}")
    // *

    // */
   // public $post_code;




}