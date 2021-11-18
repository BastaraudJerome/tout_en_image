<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\Comments;
use App\Entity\PostLike;
use App\Form\CommentsType;
use App\Form\UserVideoType;
use App\Repository\VideoRepository;
use App\Repository\CategoryRepository;
use App\Repository\PostLikeRepository;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
            $this->addFlash('success', "Votre video a bien été enregistré.");
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
     *@Route("/video/videocomments/{id}", name="VideoComments") 
     */
    public function videoComments(Request $request, VideoRepository $videoRepository, int $id)
    {
        $video = $videoRepository->findOneBy(['id' =>$id]);
        if (!$video){
            throw new NotFoundHttpException('Pas de question trouvée');
        }
        

        //partie commentaire
        //on crée le commentaire vierge
        $comment = new Comments;

        // on génère le formulaire
        $form = $this->createForm(CommentsType::class, $comment);

        $form->handleRequest($request);

        //traitement du formulaire
        if($form->isSubmitted() && $form->isValid()){
            $comment->setCreatedAt(new DateTimeImmutable());
            $comment->setVideos($video);

            // on récupere le contenus du champ parentid
            $parentid = $form->get("parentid")->getData();

            // on va chercher le commentaire correspondant
            $em = $this->getDoctrine()->getManager();

            if($parentid != null ){
                $parent = $em->getRepository(Comments::class)->find($parentid);
            }
            

            //on définit le parent
            $comment->setParent($parent ?? null );

            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Votre commentaire a bien été envoyé');
            return $this->redirectToRoute('VideoComments', ['id' => $id]);
        }

        return $this->render('video/video-comments.html.twig', [
            'videos' => $videoRepository->find($id), 
            'form' => $form->createView(),
        ]);
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
    /**
     * Permet de like ou unliker une video
     *
     * @Route("/video/{id}/like", name="video_like")
     * 
     * @param Video $video
     * @param ObjectManager $manager
     * @param PostLikeRepository $postLikeRepository
     * @return Response
     */
    public function like(Video $video, PostLikeRepository $postLikeRepository) : Response
    {
        $user = $this->getUser();

        if(!$user) return $this->json([
            'code' => 403,
            'message' => "Vous n'êtes pas autorisé"
        ],403);

        if($video->isLikedByUser($user)){
            $like = $postLikeRepository->findOneBy([
                'videoLike'=> $video,
                'user' => $user
            ]);
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message'=> 'Like bien supprimé',
                'likes' => $postLikeRepository->count(['videoLike'=> $video])
            ], 200);
        }

        $like = new PostLike();
        $like->setVideoLike($video)
             ->setUser($user);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($like);
        $manager->flush();

        return $this->json([
            'code'=> 200, 
            'message'=> 'Like bien ajouté',
            'likes' => $postLikeRepository->count(['videoLike' => $video])
        ], 200);

    }
}
