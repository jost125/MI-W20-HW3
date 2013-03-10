<?php

namespace CarRegistry\ApiBundle\Controller;

use CarRegistry\ApiBundle\DAO\OwnerDAO;
use Doctrine\DBAL\DBALException;
use CarRegistry\ApiBundle\Entity\Owner;
use CarRegistry\ApiBundle\JsonDecoder;
use Exception;
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
	 * @Route("/")
	 * @Method("POST")
	 */
	public function postAction(Request $request) {
		$owner = new Owner();
		$this->jsonDecoder->decodeAndFill($request->getContent(), $owner);

		try {
			$this->ownerDAO->save($owner);
			$responseData = array('id' => $owner->getId());
		} catch (DBALException $ex) {
			$responseData = array('error' => preg_match('~Duplicate entry~', $ex->getMessage()) ? 'Duplicate entry' : 'Unknown error occurred');
		} catch (Exception $ex) {
			$responseData = array('error' => 'Unknown error occurred');
		}

		return $this->response($responseData);
	}

	private function response($responseData) {
		return new JsonResponse(array(
			'response' => $responseData,
		));
	}

}
