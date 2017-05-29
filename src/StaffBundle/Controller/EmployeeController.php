<?php

namespace StaffBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class EmployeeController extends Controller
{
    /**
     * @Route("/employees")
     */
    public function listAction()
    {
        return new JsonResponse(['message' => 'Employee controller']);
    }
}
