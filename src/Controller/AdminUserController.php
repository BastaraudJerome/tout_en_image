<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserProfileType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/admin/user")
 */
class AdminUserController extends AbstractController
{
    /**
     * @Route("/liste/{role}", name="admin_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, string $role): Response
    {
        return $this->render('admin_user/index.html.twig', [
            'users' => $userRepository->getByRole($role),
            'title' => ($role == 'user') ? 'utilisateurs' : 'administrateurs',
            'role'  => $role,
        ]);
    }

    /**
     * @Route("/new/{role}", name="admin_user_new", methods={"GET","POST"})
     */
    public function new(Request $request, string $role, UserPasswordHasherInterface $uphi): Response
    {
        $user = new User();
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($role=='user'){
                $user->setRoles(["ROLE_USER"]);
            }elseif($role=='asso'){
                $user->setRoles(["ROLE_USER", "ROLE_ASSO"]);
            }else{
                $user->setRoles(["ROLE_USER", "ROLE_ASSO","ROLE_ADMIN"]);
            }    
            $user->setPassword($uphi->hashPassword($user, $user->getPlainPassword()));
            $user->setIsVerified(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_index', ["role"=>$role], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_user/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'role' => $role
        ]);
    }

    /**
     * @Route("/{id}", name="admin_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        if(in_array("ROLE_ADMIN", $user->getRoles())){
            $role="admin";
        }
        else if(in_array("ROLE_ASSO", $user->getRoles())){
            $role= "asso";
        }
        else{
            $role = "user";
        }
        return $this->render('admin_user/show.html.twig', [
            'user' => $user,
            'role' => $role
        ]);
    }

    /**
     * @Route("/{id}/edit/{role}", name="admin_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, string $role): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $userForData = $request->request->get("user");//on recupère les données du formulaire posté dans un tableau
            $roleToCheck = $userForData["role"]; // on récupère la valeur du choiceType de la proprieté $role dans le formulaire
            if($roleToCheck === "ROLE_ADMIN"){
                
                $user->setRoles(["ROLE_USER", "ROLE_ASSO", "ROLE_ADMIN"]);

            }elseif($roleToCheck === "ROLE_ASSO"){

                $user->setRoles(["ROLE_USER", "ROLE_ASSO"]);
            
            }else{

                $user->setRoles(["ROLE_USER"]);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_user_index', ["role"=> $role ], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('admin_user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'title' => ($role == "user") ? 'utilisateur' : 'administrateur',
            'role' => $role,
        ]);
    }

    /**
     * @Route("/{id}/{role}", name="admin_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, string $role): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user_index', ["role" =>$role], Response::HTTP_SEE_OTHER);
    }
}
