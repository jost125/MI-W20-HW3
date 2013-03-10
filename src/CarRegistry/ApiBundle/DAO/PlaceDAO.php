<?php

namespace CarRegistry\ApiBundle\DAO;

use Doctrine\ORM\EntityManager;
use CarRegistry\ApiBundle\Entity\Place;
use Doctrine\ORM\NoResultException;
use CarRegistry\ApiBundle\Entity\Owner;

class PlaceDAO {

	private $em;

	public function __construct(EntityManager $em) {
		$this->em = $em;
	}

	public function getAllForOwner(Owner $owner) {
		return $this->em->createQuery('
			SELECT p FROM CarRegistryApiBundle:Place p
			JOIN p.owner o
			WHERE o.id = :ownerId
		')->setParameter('ownerId', $owner->getId())
			->getArrayResult();
	}

	public function save(Place $place) {
		$this->em->persist($place);
		$this->em->flush();
	}

	public function getOne($ownerId, $placeId) {
		$results = $this->em->createQuery('
			SELECT p FROM CarRegistryApiBundle:Place p
			JOIN p.owner o
			WHERE p.id = :placeId
			AND o.id = :ownerId
		')->setParameter('placeId', $placeId)
			->setParameter('ownerId', $ownerId)
			->getArrayResult();

		if (!count($results)) throw new NoResultException();

		return $results[0];
	}

	public function getOneEntity($ownerId, $placeId) {
		$owner = $this->em->createQuery('
			SELECT p FROM CarRegistryApiBundle:Place p
			JOIN p.owner o
			WHERE p.id = :placeId
			AND o.id = :ownerId
		')->setParameter('placeId', $placeId)
			->setParameter('ownerId', $ownerId)
			->getSingleResult();

		return $owner;
	}

	public function delete($ownerId, $placeId) {
		$this->em->remove($this->getOneEntity($ownerId, $placeId));
		$this->em->flush();
	}

}
