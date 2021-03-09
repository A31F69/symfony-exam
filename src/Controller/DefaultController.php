<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ContactRepository $repo): Response
    {
        $contacts = $repo->findAll();

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'contacts' => $contacts
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, EntityManagerInterface $manager, Contact $contact = null): Response
    {
        if(empty($contact)) {
            $contact = new Contact();
        }

        $form = $this->createForm(ContactType::class, $contact, [
            'method' => 'POST',
        ]);


        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($contact);
            $manager->flush();

            return $this->redirectToRoute('index');
        }
        return $this->render('default/contact.html.twig', [
            'formContact' => $form->createView(),
        ]);
    }


    // /**
    //  * @Route("/contact", name="contact")
    //  */
    //     public function form(Contact $contact = null, EntityManagerInterface $manager) {
        
    //     $contact = new Contact();

    //     $contact->setEmail('test@test.com');
    //     $contact->setSubject('Ceci est un test');
    //     $contact->setMessage('Un message de test, pouvant être long, ou non. Celui-ci ne l\'est pas :) .');

    //     $manager->persist($contact);
    //     $manager->flush();
    // }

}