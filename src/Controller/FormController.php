<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class FormController extends AbstractController
{
    #[Route('/', name: 'welcome')]
    public function index(): Response
    {
        return $this->render('index.html.twig', [
            'welcomeMessage' => 'Üdvözlünk az oldalunkon!',
            'contactMessage' => 'A gombra kattintva kapcsolatba léphetsz velünk'
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function show(Environment $twig, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactFormType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            return new Response($twig->render('/contact/show.html.twig', [
                'success_message' => 'Köszönjük szépen a kérdésedet. Válaszunkkal hamarosan keresünk a megadott e-mail címen.'
            ]));
        }

        if ($form->isSubmitted()) {

            $errors = $validator->validate($contact);

            if (count($errors) > 0) {

                return new Response($twig->render('/contact/show.html.twig', [
                    'error_message' => 'Hiba! Kérjük töltsd ki az összes mezőt',
                    'contact_form' => $form->createView()
                ]));
            }
        }

        return new Response($twig->render('/contact/show.html.twig', [
            'contact_form' => $form->createView()
        ]));
    }
}
