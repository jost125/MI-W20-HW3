<?php

namespace CarRegistry\ApiBundle\Controller;

use CarRegistry\ApiBundle\DAO\OwnerDAO;
use Doctrine\DBAL\DBALException;
use CarRegistry\ApiBundle\Entity\Owner;
use CarRegistry\ApiBundle\JsonDecoder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/v1/owner", service="controller.owner_controller");
 */
class OwnerController {

	private $ownerDAO;
	private $jsonDecoder;

	public function __construct(OwnerDAO $ownerDAO, JsonDecoder $jsonDecoder) {
		$this->ownerDAO = $ownerDAO;
		$this->jsonDecoder = $jsonDecoder;
	}

	/**
	 * @Route("/")
	 * @Method("GET")
	 */
	public function getAction() {
		return $this->response($this->ownerDAO->getAll());
	}

	/**
	 * @Route("/{id}")
	 * @Method("GET")
	 */
	public function getOneAction($id) {
		return $this->response($this->ownerDAO->getOne($id));
	}

	/**
	 * @Route("/")
	 * @Method("POST")
	 */
	public function postAction(Request $request) {
		$owner = new Owner();
		return $this->response($this->save($request, $owner));
	}

	/**
	 * @Route("/{id}")
	 * @Method("PUT")
	 */
	public function putAction(Request $request, $id) {
		$owner = $this->ownerDAO->getOneEntity($id);
		return $this->response($this->save($request, $owner));
	}

	/**
	 * @Route("/{id}")
	 * @Method("DELETE")
	 */
	public function deleteAction($id) {
		$this->ownerDAO->delete($id);
		$responseData = array(
			'message' => 'deleted',
			'id' => $id,
		);

		return $this->response($responseData);
	}

	private function save(Request $request, Owner $owner) {
		$this->jsonDecoder->decodeAndFill($request->getContent(), $owner);
		$this->ownerDAO->save($owner);
		return array(
			'id' => $owner->getId(),
			'message' => 'updated',
		);
	}

	private function response($responseData) {
		return new JsonResponse(array(
			'response' => $responseData,
		));
	}

}
