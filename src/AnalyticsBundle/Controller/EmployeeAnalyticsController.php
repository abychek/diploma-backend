<?php

namespace AnalyticsBundle\Controller;

use AppBundle\Controller\AbstractController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AnalyticsController
 * @package AnalyticsBundle\Controller
 * @Route("/employees")
 */
class EmployeeAnalyticsController extends AbstractController
{
    /**
     * @ApiDoc(
     *  section="Analytics",
     *  resource=true,
     *  description="Returns list of employees with needed technologies and sorted by open projects count from low to big.",
     *  filters={
     *      {"name"="size", "dataType"="integer", "description"="Size of returned data"},
     *      {"name"="from", "dataType"="integer", "description"="Start position of returned data"},
     *      {
     *          "name"="technologies",
     *          "dataType"="array",
     *          "pattern"="{technology_id1},{technology_id2},{technology_id3}, ...",
     *          "description"="Filtrate employees by Technologies."
     *      }
     *  }
     * )
     * @Route("/most-free")
     * @Method({"GET"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mostFreeAction(Request $request)
    {
        $options = $this->handleOptions($request);
        $technologies = explode(',', $request->get('technologies', ''));
        $employees = $this
            ->getDoctrine()
            ->getRepository('StaffBundle:Employee')
            ->mostFreeByTechnologies($technologies, $options);
        $response = [];
        foreach ($employees as $employee) {
            $response[] = array_merge($employee[0]->toArray(), ['project_count' => $employee['projectCount']]);
        }

        return new JsonResponse($response);
    }
}
