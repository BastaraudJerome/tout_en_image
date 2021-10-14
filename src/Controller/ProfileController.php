<?php

namespace App\Controller;

use App\Form\UserProfileType;
use App\Repository\PhotoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(Request $request, UserPasswordHasherInterface $uphi): Response
    {
        // Mise en place du formulaire permettant la modification des informations de l'utilisateurs
        $user = $this->getUser();// on recupere le user connecté
        $profileForm = $this->createForm(UserProfileType::class, $user);
        // On verifie la posibilité d'hydrate (de remplir) le formulaire avec les données se trouvant dans la requete
        $profileForm->handleRequest($request);
        //si on a pu hydrater le formulaire on verifie si il est envoyer et surtout valide
        if($profileForm->isSubmitted() && $profileForm->isValid()){
            $plainPassword = $profileForm->getData()->getPlainPassword();
            if(!is_null($plainPassword)){
                $encodePassword = $uphi->hashPassword($user, $plainPassword);
                $user->setPassword($encodePassword);
                $this->addFlash('warning', "Votre mot de passe a été mis à jour.");
            }
            // on recupere un entité manager pour pouvoir gerer la mise en BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            // On met en place un flashMessage
            $this->addFlash('success', "Votre Profil a été mis à jour.");
            // On redirige sur la route profile (oui c la meme page) ce qui permet à Symfony de supprimer les msg lorsqu'ilon été affiché par le twig, sinon il reste en memoire ainsi que les informations du formulaire de l'utilisateur se trouvant dans la request de sorte que l'on recharge la page, et les modif sont recharcher continullement avec les alert qui s'affiche
            return $this->redirectToRoute("profile");
        }  
        return $this->render('profile/index.html.twig', [
            'form' => $profileForm->createView(),
        ]);
    }

    // /**
    //  * @Route("/profile/addfavori")
    //  */
    // public function addFavori(Request $request, PhotoRepository $photoRepository): Response
    // {
    //     // on recupoere l'id du livre envoyer par ajax
    //     $photoId = $request->request->get("id");
    //     // on recupere le livre
    //     $photo = $photoRepository->find($photoId);
    //     // on recupere le user connecteé
    //     $user = $this->getUser();
    //     // on ajoute le livre dans la liste de l'utilisateur
    //     $user->addPhotoList($photo);
    //     // on recuper un entity manager pour faire un persist et un flush
    //     $em =$this->getDoctrine()->getManager();
    //     $em->persist($user);
    //     $em->flush();        
    //     // on retourne une reponse
    //     return new Response('ok');
    // }

     /**
    * @Route("/profile/removefavori/{id}", name="deleteLivreListe")
    */
    public function removeFavori(int $id, PhotoRepository $photoRepository): Response
    {
        // on recupere le livre
        $photo = $photoRepository->find($id);
        // on recupere le user connecteé
        $user = $this->getUser();
        // on supprime le livre dans la liste de l'utilisateur
        $user->removePhotoList($photo);
        // on recuper un entity manager pour faire un persist et un flush
        $em =$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        
        return $this->redirectToRoute('profile');
    }
}
