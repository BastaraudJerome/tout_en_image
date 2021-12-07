<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\RawMessage;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        //Mise en place du formulaire pour le rendu coté front
        $contact = new Contact();
        $form = $this->createForm(ContactType::class);
        // hydratation du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();
//dd($form->getData());
            $message = (new Email())
                ->from($contactFormData->getEmail())
                ->to('toutenimage77@gmail.com')
                ->subject('vous avez reçu un email')
                ->text(
                    'Envoyé par : ' . $contactFormData->getEmail() . \PHP_EOL .
                    'Sujet : ' . $contactFormData->getSujet()
                    . \PHP_EOL . $contactFormData->getMessage(),
                    'text/plain'
                );
            $mailer->send($message);
            $this->addFlash('success', 'Vore message a été envoyé');
            return $this->redirectToRoute('contact');
        }
        //Renvoi de la vue
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
