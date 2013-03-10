<?php

namespace CarRegistry\ApiBundle\Controller;

use CarRegistry\ApiBundle\DAO\PlaceDAO;
use CarRegistry\ApiBundle\DAO\OwnerDAO;
use Doctrine\DBAL\DBALException;
use CarRegistry\ApiBundle\Entity\Place;
use CarRegistry\ApiBundle\JsonDecoder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/v1/owner/{ownerId}/place", service="controller.place_controller");
 */
class PlaceController {

	private $placeDAO;
	private $jsonDecoder;
	private $ownerDAO;

	public function __construct(PlaceDAO $placeDAO, OwnerDAO $ownerDAO, JsonDecoder $jsonDecoder) {
		$this->placeDAO = $placeDAO;
		$this->jsonDecoder = $jsonDecoder;
		$this->ownerDAO = $ownerDAO;
	}

	/**
	 * @Route("/")
	 * @Method("GET")
	 */
	public function getAction($ownerId) {
		$owner = $this->ownerDAO->getOneEntity($ownerId);
		return $this->response($this->placeDAO->getAllForOwner($owner));
	}

	/**
	 * @Route("/{placeId}")
	 * @Method("GET")
	 */
	public function getOneAction($ownerId, $placeId) {
		return $this->response($this->placeDAO->getOne($ownerId, $placeId));
	}

	/**
	 * @Route("/")
	 * @Method("POST")
	 */
	public function postAction(Request $request, $ownerId) {
		$owner = $this->ownerDAO->getOneEntity($ownerId);
		$place = new Place();
		$place->setOwner($owner);
		return $this->response($this->save($request, $place));
	}

	/**
	 * @Route("/{placeId}")
	 * @Method("PUT")
	 */
	public function putAction(Request $request, $ownerId, $placeId) {
		$owner = $this->placeDAO->getOneEntity($ownerId, $placeId);
		return $this->response($this->save($request, $owner));
	}

	/**
	 * @Route("/{placeId}")
	 * @Method("DELETE")
	 */
	public function deleteAction($ownerId, $placeId) {
		$this->placeDAO->delete($ownerId, $placeId);
		$responseData = array(
			'message' => 'deleted',
			'id' => $placeId,
		);

		return $this->response($responseData);
	}

	private function save(Request $request, Place $place) {
		$this->jsonDecoder->decodeAndFill($request->getContent(), $place);
		$this->placeDAO->save($place);
		return array(
			'id' => $place->getId(),
			'message' => 'updated',
		);
	}

	private function response($responseData) {
		return new JsonResponse(array(
			'response' => $responseData,
		));
	}

}
