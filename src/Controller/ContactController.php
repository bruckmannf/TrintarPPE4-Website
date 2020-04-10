<?php


namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * My first contact page
     *
     * @param Request $request the Symfony request
     *
     * @return Response
     */                                       // On injecte le service d'envoi d'email
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
        $contact = new Contact();
        $contact->setSubject("b");

        $form = $this->createForm(ContactType::class, $contact, []);

        if ('POST' === $request->getMethod()) {

            $form->handleRequest($request);
            if ($form->isValid()) {

                $contact = $form->getData();
                // On génère un nouveau Objet Php \Swift_Message
                $message = (new \Swift_Message('Mon premier email via Symfony')) // Le sujet
                ->setFrom('noreply@test.fr') // L'email d'envoi
                ->setTo('test@test.fr') // L'email destinataire
                // Le contenu de l'email, qu'on va générer à partir d'un twig
                ->setBody(
                // Utilisation du renderView au lieu du render
                // Permettant de renvoyer uniquement le html et non un objet Response
                    $this->renderView(
                        'Contact/contact-email.html.twig',
                        ['contact' => $contact]
                    ),
                    'text/html'
                )
                ;

                // On utilise le mailer pour envoyer notre \Swift_Message
                $mailer->send($message);

                // Hop on fait un rendu dans un twig
                return $this->render("Contact/contact-recap.html.twig", [
                    'contact' => $contact
                ]);
            }
        }

        return $this->render("Contact/contact.html.twig", [
            'form' => $form->createView(),
        ]);
    }
}
