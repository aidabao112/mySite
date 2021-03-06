<?php

namespace Site\MainBundle\Controller;

use Site\MainBundle\Utils\GithubUtil;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    public function indexAction($_locale) {
        $urlFr = $this->generateUrl('main_homepage', array('_locale' => 'fr'));
        $urlEn = $this->generateUrl('main_homepage', array('_locale' => 'en'));
        $today = new \DateTime();
        $birthday = new \DateTime('1990-02-07');
        $diff = $today->diff($birthday);
        return $this->render('MainBundle:Default:index.html.php',array(
            'activeIndex' => true,
            'urlFr' => $urlFr,
            'urlEn' => $urlEn,
            'locale' => $_locale,
            'age' => $diff->y
        ));
    }

    public function skillsAction($_locale) {
        $urlFr = $this->generateUrl('skills_page', array('_locale' => 'fr'));
        $urlEn = $this->generateUrl('skills_page', array('_locale' => 'en'));
        return $this->render('MainBundle:Skills:skills.html.php',array(
            'activeSkills' => true,
            'urlFr' => $urlFr,
            'urlEn' => $urlEn,
            'locale' => $_locale
        ));
    }

    public function experienceAction($_locale) {
        $urlFr = $this->generateUrl('experience_page', array('_locale' => 'fr'));
        $urlEn = $this->generateUrl('experience_page', array('_locale' => 'en'));
        $urlExperienceGithub = $this->generateUrl('experience_github');

        return $this->render('MainBundle:Default:experience.html.php',array(
            'activeExp' => true,
            'urlFr' => $urlFr,
            'urlEn' => $urlEn,
            'urlExperienceGithub' => $urlExperienceGithub,
            'locale' => $_locale,
            'scripts' => array(
                'js/experienceGithubCtrl.js'
            )
        ));
    }

    public function experienceGithubAction(Request $request) {
        $response = new Response();
        if ($request->isXmlHttpRequest() && $request->getMethod() !== 'GET') {
            return $response;
        }

        // array('html_url' => '', 'avatar_url' => '', 'login' => '', 'followers' => '', 'following' => '', 'public_repos' => '')
        $buzz = $this->container->get('buzz');
        $githubUtil = new GithubUtil($buzz);
        $tabContrib = array();
        try {
            $tabContrib = $githubUtil->getContributedRepository();
        }
        catch(\Exception $e) {}

        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode(array(
            'response' => $githubUtil->getProfilInformation(),
            'contrib' => $tabContrib
        )));
        return $response;
    }

    public function contactAction(Request $request, $_locale) {
        if ($request->isXmlHttpRequest() && $request->getMethod() === 'POST') {
            $response = new Response();
            $data = json_decode($request->getContent(),true);
            $result = $this->sendContact($data) ? 'OK' : 'ERROR';
            $message = ($result === 'OK') ? $this->get('translator')->trans('contact_msgResultSuccess')
            : $this->get('translator')->trans('contact_msgResultFail');
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent(json_encode(array(
                'message' => $result,
                'userMsg' => $message
            )));
            return $response;
        }

        $urlFr = $this->generateUrl('contact_page', array('_locale' => 'fr'));
        $urlEn = $this->generateUrl('contact_page', array('_locale' => 'en'));
        return $this->render('MainBundle:Default:contact.html.php',array(
            'activeContact' => true,
            'urlFr' => $urlFr,
            'urlEn' => $urlEn,
            'locale' => $_locale,
            'scripts' => array(
                'js/contactService.js',
                'js/contactCtrl.js'
            )
        ));
    }

    private function sendContact($data) {
        if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        if (!isset($data['message']) || count($data['message']) === 0) {
            return false;
        }
        if (!isset($data['objet'])) {
            $data['objet'] = 'Demande de contact';
        }
        $data['message'] = strip_tags($data['message']);
        $data['objet'] = strip_tags($data['objet']);
        $to = $this->container->getParameter('mailer_to');
        $from = $this->container->getParameter('mailer_user');
        $message = \Swift_Message::newInstance()
            ->setSubject($data['objet'])
            ->setFrom($from)
            ->setTo($to)
            ->setBody($this->renderView('MainBundle:Default:template_email.html.php', array(
                'objet' => $data['objet'],
                'email' => $data['email'],
                'message' => $data['message']
            )),'text/html');
        return $this->get('mailer')->send($message);
    }
}
