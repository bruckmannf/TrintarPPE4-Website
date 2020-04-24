<?php


namespace App\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailerService extends AbstractController
{
    /**
     * @param \Swift_Mailer $mailer
     * @param $token
     * @param $username
     * @param $template
     * @param $to
     */
    public function sendToken(\Swift_Mailer $mailer, $token, $to, $username, $template)
    {
        $message = (new \Swift_Message('Mail de confirmation'))
            ->setFrom('registration@al-houria.com')
            ->setTo($to)
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'emails/'.$template,
                    [
                        'token' => $token,
                        'username' => $username
                    ]
                ),
                'text/html'
            )
        ;
        $mailer->send($message);
    }
}