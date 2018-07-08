<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \AppBundle\Entity\Sponsor;

/**
 * Class AdminSponsorController.
 *
 * @Route("/kontrollpanel")
 */
class AdminSponsorController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/cp_sponsors", name="cp_sponsors")
     * @Method({"GET", "POST"})
     */
    public function showSponsorsAction(Request $request)
    {
        $currentClub = $this->get("club_manager")->getCurrentClub();
        $sponsors = $this->getDoctrine()->getRepository(Sponsor::class)->findAllByClub($currentClub);
        return $this->render("sponsor/manage.html.twig", [
            "sponsors" => $sponsors
        ]);
    }
}
