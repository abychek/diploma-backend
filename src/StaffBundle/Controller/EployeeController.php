<?php

namespace StaffBundle\Controller;

use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class EployeeController extends Controller implements ClassResourceInterface
{
    /**
     * @Route("/employees")
     */
    public function listAction()
    {
        return $this->render('StaffBundle:Employee:list.html.twig');
    }
}
