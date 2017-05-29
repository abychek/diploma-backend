<?php

namespace StaffBundle\Controller;

use AppBundle\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class EmployeeController extends Controller
{
    /**
     * @Route("/employees")
     */
    public function listAction(Request $request)
    {
        $result = [];
        $options = $this->handlePagination($request);
        $employees = $this->getDoctrine()->getRepository('StaffBundle:Employee')->getSortedByName($options);
        foreach ($employees as $employee) {
            $result[] = $employee->toArray();
        }

        return new JsonResponse($result);
    }

    private function handlePagination(Request $request)
    {
        return [
            ResourceRepository::OPTION_FROM => $request->get('from') ? : 0,
            ResourceRepository::OPTION_SIZE => $request->get('size') ? : 100
        ];
    }
}
