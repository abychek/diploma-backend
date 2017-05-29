<?php

namespace AppBundle\Controller;


use AppBundle\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RestController extends Controller
{
    protected function handlePagination(Request $request)
    {
        return [
            ResourceRepository::OPTION_FROM => $request->get('from') ? : 0,
            ResourceRepository::OPTION_SIZE => $request->get('size') ? : 100
        ];
    }
}