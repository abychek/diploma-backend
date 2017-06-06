<?php

namespace AppBundle\Controller;


use AppBundle\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    protected function handleOptions(Request $request)
    {
        return [
            ResourceRepository::OPTION_FROM => $this->getFrom($request),
            ResourceRepository::OPTION_SIZE => $this->getSize($request)
        ];
    }

    /**
     * @param Request $request
     * @return string
     */
    private function getFrom(Request $request)
    {
        return $this->getOption($request, ResourceRepository::OPTION_FROM, 0);
    }

    /**
     * @param Request $request
     * @return string
     */
    private function getSize(Request $request)
    {
        return $this->getOption($request, ResourceRepository::OPTION_SIZE, 10);
    }

    /**
     * @param Request $request
     * @param $param
     * @param $default
     * @return string
     */
    private function getOption(Request $request, $param, $default)
    {
        return $request->get($param, $default);
    }
}