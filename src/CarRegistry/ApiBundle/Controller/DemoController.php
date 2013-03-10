<?php

namespace CarRegistry\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DemoController {

	/**
	 * @Route("/", name="_demo")
	 */
	public function indexAction() {
		return new JsonResponse(array('Hello World'));
	}

}
