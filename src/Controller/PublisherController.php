<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\Update;

class PublisherController extends AbstractController
{
    #[Route('/publisher/{topic}', name: 'publisher', methods: [Request::METHOD_POST],)]
    public function index(HubInterface $hub, $topic, Request $request)
    {

        return new Response('success');
    }
}
