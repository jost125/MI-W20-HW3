<?php

namespace CarRegistry\ApiBundle\DAO;

use CarRegistry\ApiBundle\Entity\Car;
use CarRegistry\ApiBundle\Entity\Owner;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;

class CarDAO {

	private $em;

	public function __construct(EntityManager $em) {
		$this->em = $em;
	}

	public function getAllForOwner(Owner $owner) {
		return $this->em->createQuery('
			SELECT c FROM CarRegistryApiBundle:Car c
			JOIN c.owner o
			WHERE o.id = :ownerId
		')->setParameter('ownerId', $owner->getId())
			->getArrayResult();
	}

	public function save(Car $car) {
		$this->em->persist($car);
		$this->em->flush();
	}

	public function getOne($ownerId, $carId) {
		$results = $this->em->createQuery('
			SELECT c FROM CarRegistryApiBundle:Car c
			JOIN c.owner o
			WHERE c.id = :carId
			AND o.id = :ownerId
		')->setParameter('carId', $carId)
			->setParameter('ownerId', $ownerId)
			->getArrayResult();

		if (!count($results)) throw new NoResultException();

		return $results[0];
	}

	public function getOneEntity($ownerId, $carId) {
		$car = $this->em->createQuery('
			SELECT c FROM CarRegistryApiBundle:Car c
			JOIN c.owner o
			WHERE c.id = :carId
			AND o.id = :ownerId
		')->setParameter('carId', $carId)
			->setParameter('ownerId', $ownerId)
			->getSingleResult();

		return $car;
	}

	public function delete($ownerId, $carId) {
		$this->em->remove($this->getOneEntity($ownerId, $carId));
		$this->em->flush();
	}

}
