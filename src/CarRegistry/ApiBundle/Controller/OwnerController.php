<?php

namespace CarRegistry\ApiBundle\Controller;

use CarRegistry\ApiBundle\DAO\OwnerDAO;
use Doctrine\ORM\NoResultException;
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
	 * @Route("/{id}")
	 * @Method("GET")
	 */
	public function getOneAction($id) {
		try {
			return $this->response($this->ownerDAO->getOne($id));
		} catch (NoResultException $ex) {
			return $this->response(array('error' => 'No result found'));
		}
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
		try {
			$this->ownerDAO->delete($id);
			$responseData = array(
				'message' => 'deleted',
				'id' => $id,
			);
		} catch (NoResultException $ex) {
			$responseData = array('error' => 'No result found');
		}

		return $this->response($responseData);
	}

	private function save(Request $request, Owner $owner) {
		$this->jsonDecoder->decodeAndFill($request->getContent(), $owner);
		try {
			$this->ownerDAO->save($owner);
			return array(
				'id' => $owner->getId(),
				'message' => 'updated',
			);
		}
		catch (DBALException $ex) {
			return array('error' => preg_match('~Duplicate entry~', $ex->getMessage()) ? 'Duplicate entry' : 'Unknown error occurred');;
		}
	}

	private function response($responseData) {
		return new JsonResponse(array(
			'response' => $responseData,
		));
	}

}
