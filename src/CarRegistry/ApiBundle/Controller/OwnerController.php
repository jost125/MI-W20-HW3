<?php

namespace CarRegistry\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/v1/owner", service="controller.owner_controller");
 */
class OwnerController {

	/**
	 * @Route("/")
	 * @Method("GET")
	 */
	public function indexAction() {
		$owners = array(
			array(
				'id' => 1,
				'name' => 'John Doe',
			),
			array(
				'id' => 2,
				'name' => 'Arthur Dent',
			),
		);
		return new JsonResponse($owners);
	}

}
