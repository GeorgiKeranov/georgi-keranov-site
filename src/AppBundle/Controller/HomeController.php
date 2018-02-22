<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SiteText;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{

    private function getSiteText($name) {
        $siteText = $this->getDoctrine()
            ->getRepository(SiteText::class)
            ->findOneBy(['name' => $name]);

        if($siteText != null) {
            return $siteText->getText();
        }
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // Get texts for large text and description
        $largeText = $this->getSiteText('home_page_text');
        $description = $this->getSiteText('home_page_description');

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'large_text' => $largeText,
            'description' => $description
        ]);
    }
}
