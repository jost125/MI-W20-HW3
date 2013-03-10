<?php

namespace CarRegistry\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route(service="controller.owner_controller");
 */
class OwnerController {

	/**
	 * @Route("/", name="_demo")
	 */
	public function indexAction() {
		return new JsonResponse(array('Hello world'));
	}

}
