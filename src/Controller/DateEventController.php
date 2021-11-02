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
    public function index(Request $request): Response
    {

        $events = $this->getDoctrine() 
                        ->getRepository(Planning::class) 
                        ->findAll(); 
          //$temp = [];               
        

                

                $temp = [];
                foreach ($events as $event) {

                //if ($request->isXmlHttpRequest()) {
                    $date = $event->getDate()->format('d F Y');
                    $horaire = $event->getHoraire()->format('H:i:s');
                    $temp[] = array(
                        'id' => $event->getId(),
                        'cours' => $event->getCours(),
                        'lieux' => $event->getLieux(),
                        'date' => $date,
                        'horaire' => $horaire,
                    );
                }
                

                return new JsonResponse($temp);     
            //} 
            
        //dd($event);
        return $this->render('date_event/index.html.twig', [

            'event' => $events

        ]);
    }
}