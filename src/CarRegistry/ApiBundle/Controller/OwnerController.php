<?php

namespace CarRegistry\ApiBundle\Controller;

use CarRegistry\ApiBundle\DAO\OwnerDAO;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/v1/owner", service="controller.owner_controller");
 */
class OwnerController {

	private $ownerDAO;

	public function __construct(OwnerDAO $ownerDAO) {
		$this->ownerDAO = $ownerDAO;
	}

	/**
	 * @Route("/")
	 * @Method("GET")
	 */
	public function indexAction() {
		return new JsonResponse($this->ownerDAO->getAll());
	}

}
