<?php

namespace CarRegistry\ApiBundle\Controller;

use CarRegistry\ApiBundle\DAO\CarDAO;
use CarRegistry\ApiBundle\Entity\Car;
use CarRegistry\ApiBundle\DAO\OwnerDAO;
use Doctrine\DBAL\DBALException;
use CarRegistry\ApiBundle\JsonDecoder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/v1/owner/{ownerId}/car", service="controller.car_controller");
 */
class CarController {

	private $carDAO;
	private $jsonDecoder;
	private $ownerDAO;

	public function __construct(CarDAO $carDAO, OwnerDAO $ownerDAO, JsonDecoder $jsonDecoder) {
		$this->carDAO = $carDAO;
		$this->jsonDecoder = $jsonDecoder;
		$this->ownerDAO = $ownerDAO;
	}

	/**
	 * @Route("/")
	 * @Method("GET")
	 */
	public function getAction($ownerId) {
		$owner = $this->ownerDAO->getOneEntity($ownerId);
		return $this->response($this->carDAO->getAllForOwner($owner));
	}

	/**
	 * @Route("/{carId}")
	 * @Method("GET")
	 */
	public function getOneAction($ownerId, $carId) {
		return $this->response($this->carDAO->getOne($ownerId, $carId));
	}

	/**
	 * @Route("/")
	 * @Method("POST")
	 */
	public function postAction(Request $request, $ownerId) {
		$owner = $this->ownerDAO->getOneEntity($ownerId);
		$car = new Car();
		$car->setOwner($owner);
		return $this->response($this->save($request, $car));
	}

	/**
	 * @Route("/{carId}")
	 * @Method("PUT")
	 */
	public function putAction(Request $request, $ownerId, $carId) {
		$owner = $this->carDAO->getOneEntity($ownerId, $carId);
		return $this->response($this->save($request, $owner));
	}

	/**
	 * @Route("/{carId}")
	 * @Method("DELETE")
	 */
	public function deleteAction($ownerId, $carId) {
		$this->carDAO->delete($ownerId, $carId);
		$responseData = array(
			'message' => 'deleted',
			'id' => $carId,
		);

		return $this->response($responseData);
	}

	private function save(Request $request, Car $car) {
		$this->jsonDecoder->decodeAndFill($request->getContent(), $car);
		$this->carDAO->save($car);
		return array(
			'id' => $car->getId(),
			'message' => 'updated',
		);
	}

	private function response($responseData) {
		return new JsonResponse(array(
			'response' => $responseData,
		));
	}

}
