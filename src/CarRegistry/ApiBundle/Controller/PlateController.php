<?php

namespace CarRegistry\ApiBundle\Controller;

use CarRegistry\ApiBundle\DAO\PlateDAO;
use CarRegistry\ApiBundle\DAO\CarDAO;
use CarRegistry\ApiBundle\DAO\OwnerDAO;
use Doctrine\DBAL\DBALException;
use CarRegistry\ApiBundle\Entity\Plate;
use CarRegistry\ApiBundle\JsonDecoder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/v1/owner/{ownerId}/car/{carId}/plate", service="controller.plate_controller");
 */
class PlateController {

	private $plateDAO;
	private $carDAO;
	private $ownerDAO;
	private $jsonDecoder;

	public function __construct(PlateDAO $plateDAO, CarDAO $carDAO, OwnerDAO $ownerDAO, JsonDecoder $jsonDecoder) {
		$this->plateDAO = $plateDAO;
		$this->carDAO = $carDAO;
		$this->ownerDAO = $ownerDAO;
		$this->jsonDecoder = $jsonDecoder;
	}

	/**
	 * @Route("/")
	 * @Method("GET")
	 */
	public function getAction($ownerId, $carId) {
		$car = $this->carDAO->getOneEntity($ownerId, $carId);
		return $this->response($this->plateDAO->getAllForCar($car));
	}

	/**
	 * @Route("/{plateId}")
	 * @Method("GET")
	 */
	public function getOneAction($ownerId, $plateId) {
		return $this->response($this->carDAO->getOne($ownerId, $plateId));
	}

	/**
	 * @Route("/")
	 * @Method("POST")
	 */
	public function postAction(Request $request, $ownerId, $carId) {
		$car = $this->carDAO->getOneEntity($ownerId, $carId);
		$plate = new Plate();
		$plate->setCar($car);
		return $this->response($this->save($request, $plate));
	}

	/**
	 * @Route("/{plateId}")
	 * @Method("PUT")
	 */
	public function putAction(Request $request, $ownerId, $carId, $plateId) {
		$car = $this->carDAO->getOneEntity($ownerId, $carId);
		$plate = $this->plateDAO->getOneEntity($car, $plateId);
		return $this->response($this->save($request, $plate));
	}

	/**
	 * @Route("/{plateId}")
	 * @Method("DELETE")
	 */
	public function deleteAction($ownerId, $carId, $plateId) {
		$car = $this->carDAO->getOneEntity($ownerId, $carId);
		$this->plateDAO->delete($car, $plateId);
		$responseData = array(
			'message' => 'deleted',
			'id' => $plateId,
		);

		return $this->response($responseData);
	}

	private function save(Request $request, Plate $plate) {
		$this->jsonDecoder->decodeAndFill($request->getContent(), $plate);
		$this->plateDAO->save($plate);
		return array(
			'id' => $plate->getId(),
			'message' => 'updated',
		);
	}

	private function response($responseData) {
		return new JsonResponse(array(
			'response' => $responseData,
		));
	}

}
