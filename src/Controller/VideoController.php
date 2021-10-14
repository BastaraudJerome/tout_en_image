<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\UserVideoType;
use App\Repository\CategoryRepository;
use App\Repository\VideoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VideoController extends AbstractController
{
    /**
     * @Route("/video", name="video")
     */
    public function index(Request $request, VideoRepository $videoRepository): Response
    {
        $user = $this->getUser();
        $video = new Video;
        $videoForm = $this->createForm(UserVideoType::class, $video);
        $videoForm->handleRequest($request);
        if($videoForm->isSubmitted() && $videoForm->isValid()){
            $user->addVideoList($video);
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();
            $this->addFlash('success', "Votre photo a bien été enregistré.");
            return $this->redirectToRoute("video");
        };
        

        return $this->render('video/index.html.twig', [
            'video' => $videoForm->createView(),
            'videos' => $videoRepository->findAll(),
        ]);
    }

    /**
     * 
     *@Route("/video/addvideo")
     */
    public function addVideo(Request $request , VideoRepository $videoRepository): Response
    {
        $videoId = $request->request->get('id');
        $video = $videoRepository->find($videoId);
        $user = $this->getUser();
        $user->addVideoList($video);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new Response('ok');

    }
    /**
     * 
     *@Route("/video/removevideo/{id}", name="deleteVideoListe")
     */
    public function removeVideo(int $id, VideoRepository $videoRepository, Request $request): Response
    {
        $video = $videoRepository->find($id);
        $user = $this->getUser();
        $user->removeVideoList($video);
        $em = $this->getDoctrine()->getManager();
        $em->remove($video);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('profile');
    } 
}
