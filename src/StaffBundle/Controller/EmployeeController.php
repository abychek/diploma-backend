<?php

namespace StaffBundle\Controller;

use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * @RouteResource("Employee")
 */
class EmployeeController extends Controller
{
    /**
     * @Route("/employees")
     */
    public function listAction()
    {
        return $this->render('StaffBundle:Employee:list.html.twig');
    }
}
