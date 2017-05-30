<?php

namespace StaffBundle\Controller;


use AppBundle\Controller\RestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PositionController extends RestController
{
    /**
     * @Route("/positions")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function listAction(Request $request)
    {
        $result = [];
        $options = $this->handlePagination($request);
        $positions = $this->getDoctrine()->getRepository('StaffBundle:Position')->getSortedByName($options);
        foreach ($positions as $position) {
            $result[] = $position->toArray();
        }

        return new JsonResponse($result);
    }
}