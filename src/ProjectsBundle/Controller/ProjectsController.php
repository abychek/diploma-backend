<?php

namespace ProjectsBundle\Controller;

use AppBundle\Controller\RestController;
use ProjectsBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProjectsController
 * @package ProjectsBundle\Controller
 * @Route("/projects")
 */
class ProjectsController extends RestController
{
    /**
     * @Route("/")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function listAction(Request $request)
    {
        $result = [];
        $options = $this->handlePagination($request);
        $projects = $this->getDoctrine()->getRepository('ProjectsBundle:Project')->getSortedByTitle($options);
        foreach ($projects as $project) {
            $result[] = $project->toArray();
        }

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}/")
     * @Method({"GET"})
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function getAction(Request $request, $id)
    {
        if ($project = $this->getDoctrine()->getRepository('ProjectsBundle:Project')->find($id)) {
            return new JsonResponse($project->toArray());
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/")
     * @Method({"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $title = $request->get('title');
        $description = $request->get('description');
        if ($title && $description) {
            $em->persist((new Project())
                ->setTitle($title)
                ->setDescription($description)
                ->setStatus(Project::STATUS_AVAILABLE));
            $em->flush();

            return $this->generateInfoResponse(JsonResponse::HTTP_CREATED);
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}/")
     * @Method({"PUT"})
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function updateAction(Request $request, $id)
    {
        $project = $this->getDoctrine()->getRepository('ProjectsBundle:Project')->find($id);

        $title = $request->get('title', $project->getTitle());
        $description = $request->get('description', $project->getDescription());
        $status = $request->get('status', $project->getStatus());
        if ($title || $description || $status) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project->setName($title)->setPosition($description)->setStatus($status));
            $em->flush();

            return $this->generateInfoResponse(JsonResponse::HTTP_OK);
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function deleteAction(Request $request, $id)
    {
        if ($project = $this->getDoctrine()->getRepository('ProjectsBundle:Project')->find($id)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush();

            return $this->generateInfoResponse(JsonResponse::HTTP_NO_CONTENT);
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_NOT_FOUND);
    }
}
