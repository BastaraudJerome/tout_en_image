<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Photo;
use DateTimeImmutable;
use App\Entity\Comments;
use App\Entity\PostLike;
use App\Form\CommentsType;
use App\Form\UserPhotoType;
use App\Repository\PhotoRepository;
use App\Repository\PostLikeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     *@Route("/photo/photocomments/{id}", name="PhotoComments") 
     */
    public function photoComments(Request $request, PhotoRepository $photoRepository, int $id)
    {
        $photo = $photoRepository->findOneBy(['id' => $id]);
        if (!$photo) {
            throw new NotFoundHttpException('Pas de question trouvée');
        }


        //partie commentaire
        //on crée le commentaire vierge
        $comment = new Comments;

        // on génère le formulaire
        $form = $this->createForm(CommentsType::class, $comment);

        $form->handleRequest($request);

        //traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new DateTimeImmutable());
            $comment->setPhoto($photo);

            // on récupere le contenus du champ parentid
            $parentid = $form->get("parentid")->getData();

            // on va chercher le commentaire correspondant
            $em = $this->getDoctrine()->getManager();

            if ($parentid != null) {
                $parent = $em->getRepository(Comments::class)->find($parentid);
            }


            //on définit le parent
            $comment->setParent($parent ?? null);

            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Votre commentaire a bien été envoyé');
            return $this->redirectToRoute('VideoComments', ['id' => $id]);
        }

        return $this->render('photo/photo-comments.html.twig', [
            'photos' => $photoRepository->find($id),
            'form' => $form->createView(),
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
    /**
     * Permet de like ou unliker une photo
     * 
     * @Route("/photo/{id}/like", name="photo_like")
     *
     * @param Photo $photo
     * @param PostLikeRepository $postLikeRepository
     * @return Response
     */
    public function like(Photo $photo, PostLikeRepository $postLikeRepository): Response
    {
        $user = $this->getUser();

        if (!$user) return $this->json([
            'code' => 403,
            'message' => "Vous n'êtes pas autorisé"
        ], 403);

        if ($photo->isLikedByUser($user)) {
            $like = $postLikeRepository->findOneBy([
                'post' => $photo,
                'user' => $user
            ]);
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like bien supprimé',
                'likes' => $postLikeRepository->count(['post' => $photo])
            ], 200);
        }

        $like = new PostLike();
        $like->setPost($photo)
            ->setUser($user);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($like);
        $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Like bien ajouté',
            'likes' => $postLikeRepository->count(['post' => $photo])
        ], 200);
    }
}
