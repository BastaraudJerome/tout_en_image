<?php

namespace App\Controller;

use App\Entity\Planning;
use App\Form\PlanningType;
use App\Repository\PlanningRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/planning")
 */
class AdminPlanningController extends AbstractController
{
    /**
     * @Route("/", name="admin_planning_index", methods={"GET"})
     */
    public function index(PlanningRepository $planningRepository): Response
    {
        return $this->render('admin_planning/index.html.twig', [
            'plannings' => $planningRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_planning_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $planning = new Planning();
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($planning);
            $entityManager->flush();

            return $this->redirectToRoute('admin_planning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_planning/new.html.twig', [
            'planning' => $planning,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_planning_show", methods={"GET"})
     */
    public function show(Planning $planning): Response
    {
        return $this->render('admin_planning/show.html.twig', [
            'planning' => $planning,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_planning_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Planning $planning): Response
    {
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_planning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_planning/edit.html.twig', [
            'planning' => $planning,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_planning_delete", methods={"POST"})
     */
    public function delete(Request $request, Planning $planning): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planning->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($planning);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_planning_index', [], Response::HTTP_SEE_OTHER);
    }
}
