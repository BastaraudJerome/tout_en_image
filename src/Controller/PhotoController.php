<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\User;
use App\Form\UserPhotoType;
use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhotoController extends AbstractController
{
    /**
    * @Route("/photo", name="photo")
    */
    public function index(Request $request, PhotoRepository $photoRepository): Response
    {
        $user = $this->getUser();
        $photo = new Photo;
        $photoForm = $this->createForm(UserPhotoType::class, $photo);
        $photoForm->handleRequest($request);
        if($photoForm->isSubmitted() && $photoForm->isValid()){
            $user->addPhotoList($photo);
            $em = $this->getDoctrine()->getManager();
            $em->persist($photo);
            $em->flush();
            $this->addFlash('success', "Votre photo a bien été enregistré.");
            return $this->redirectToRoute("photo");
        };
        

        return $this->render('photo/index.html.twig', [
            'photo' => $photoForm->createView(),
            'photos' => $photoRepository->findAll(),
        ]);
    }
    /**
     * 
     *@Route("/photo/addphoto")
     */
    public function addPhoto(Request $request , PhotoRepository $photoRepository): Response
    {
        $photoId = $request->request->get('id');
        $photo = $photoRepository->find($photoId);
        $user = $this->getUser();
        $user->addPhotoList($photo);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new Response('ok');

    }
    /**
     * 
     *@Route("/photo/removephoto/{id}", name="deletePhotoListe")
     */
    public function removePhoto(int $id, PhotoRepository $photoRepository, Request $request): Response
    {
        $photo = $photoRepository->find($id);
        $user = $this->getUser();
        $user->removePhotoList($photo);
        $em = $this->getDoctrine()->getManager();
        $em->remove($photo);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('profile');
    } 
}
