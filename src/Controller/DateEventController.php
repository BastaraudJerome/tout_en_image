<?php

namespace App\Controller;

use App\Entity\Planning;
use App\Repository\PlanningRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DateEventController extends AbstractController
{
    /**
     * @Route("/date/event", name="date_event")
     */
    public function index(Request $request, PlanningRepository $planningRepository): Response
    {

        $events = $this->getDoctrine() 
                        ->getRepository(Planning::class) 
                        ->findAll();  
      
        if ($request->isXmlHttpRequest()) {  

             
             
            foreach($events as $event) { 
                $date = $event->getDate()->format('d F Y'); 
                $horaire = $event->getHoraire()->format('H:i:s'); 
                $temp = array(
                    'id' => $event->getId(),
                    'cours' => $event->getCours(),  
                    'lieux' => $event->getLieux(),  
                    'date' => $date,  
                    'horaire' => $horaire,  
                );   
                  
            } 
            return new JsonResponse($temp);
             

                
        }
    }
}