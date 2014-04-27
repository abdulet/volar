<?php

namespace Volar\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Overlays\Animation;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Overlays\InfoWindow;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class FrontendController extends Controller
{
    /**
     * Route("/", name="_index")
     * Template()
     */
    public function indexAction()
    {
        /*
         * The action's view can be rendered using render() method
         * or @Template annotation as demonstrated in DemoController.
         *
         */
        return $this->render('FrontendBundle:Frontend:index.html.twig', array('page' => 'index'));
    }

	public function teamAction()
	{
		return $this->render('FrontendBundle:Frontend:team.html.twig', array('page' => 'team'));
	}

	public function schoolAction()
	{
		return $this->render('FrontendBundle:Frontend:school.html.twig', array('page' => 'school'));
	}

	public function tandemAction()
	{
		return $this->render('FrontendBundle:Frontend:tandem.html.twig', array('page' => 'tandem'));
	}

	public function locationAction()
	{
		$map = $this->get('ivory_google_map.map');
		$map->setLanguage($this->getRequest()->getLocale());
		$map->setCenter(42.222058,1.32153,true);
		$map->setMapOption('zoom', 8);
		$map->setStylesheetOption('width', '422px');
		$map->setStylesheetOption('height', '365px');
		$organya = new Marker();
		$ager = new Marker();
		$berga = new Marker ();
		
		$infoOrganya = new InfoWindow();
		$infoAger = new InfoWindow();
		$infoBerga = new InfoWindow();

		$infoOrganya->setContent("<div class='infowindow'><p><strong>".$this->get('translator')->trans("Location.text9")."</strong><br/>".$this->get('translator')->trans("Location.text10")."<a href='https://maps.google.es/maps?q=42.222074,1.321464&hl=ca&ll=42.223941,1.324024&spn=0.01125,0.020256&sll=43.181147,2.175293&sspn=5.671624,10.371094&t=h&z=15&iwloc=near' target='blank'>Google Maps</a></br>".$this->get('translator')->trans("Contact.tlf").": +34 633 217 141</p></div>");
		$infoAger->setContent("<div class='infowindow'><p><strong>".$this->get('translator')->trans("Location.text6")."</strong><br/>".$this->get('translator')->trans("Location.text10")."<a href='https://maps.google.es/maps?q=Ager&hl=ca&ie=UTF8&sll=42.223941,1.324024&sspn=0.01125,0.020256&t=h&hnear=%C3%80ger,+Lleida,+Catalunya&z=15' target='blank'>Google Maps</a></br>".$this->get('translator')->trans("Contact.tlf").": +34 633 217 141</p></div>");
		$infoBerga->setContent("<div class='infowindow'><p><strong>Berga</strong><br/>".$this->get('translator')->trans("Location.text10")."<a href='https://maps.google.es/maps?q=berga&hl=ca&sll=42.000793,0.761005&sspn=0.01129,0.020256&t=h&hnear=Berga,+Barcelona,+Catalunya&z=14' target='blank'>Google Maps</a></br>".$this->get('translator')->trans("Contact.tlf").": +34 633 217 141</p></div>");
		$organya->setInfoWindow($infoOrganya);
		$ager->setInfoWindow($infoAger);
		$berga->setInfoWindow($infoBerga);

		$organya->setPosition(42.222058,1.32153,true);
		$berga->setPosition(42.112741,1.854286,true);
		$ager->setPosition(42.000772,0.760975,true);

		$map->addMarker($organya);
		$map->addMarker($berga);
		$map->addMarker($ager);

		return $this->render('FrontendBundle:Frontend:location.html.twig', array('page' => 'location', 'map' => $map));
	}

	public function weatherAction()
	{
		return $this->render('FrontendBundle:Frontend:weather.html.twig', array('page' => 'weather'));
	}

	public function contactAction()
	{
		return $this->render('FrontendBundle:Frontend:contact.html.twig', array('page' => 'contact'));
	}
}
