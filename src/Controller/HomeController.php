<?php

namespace App\Controller;

use App\Repository\HomeRepository;
use App\Repository\PhotoRepository;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PhotoRepository $photoRepository, HomeRepository $homeRepository, VideoRepository $videoRepository): Response
    {
        return $this->render('home/index.html.twig', [

            'photos' => $photoRepository->findBy([],["updatedAt"=>"DESC"], 4),
            'videos' => $videoRepository->findBy([],["updatedAt"=>"DESC"], 4),       
            'homeContent' => $homeRepository->findOneBy(["active"=>true]),
        ]);
    }
    
}
