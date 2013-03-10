<?php

namespace CarRegistry\ApiBundle\DAO;

use CarRegistry\ApiBundle\Entity\Car;
use CarRegistry\ApiBundle\Entity\Plate;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;

class PlateDAO {

	private $em;

	public function __construct(EntityManager $em) {
		$this->em = $em;
	}

	public function getAllForCar(Car $car) {
		return $this->em->createQuery('
			SELECT p FROM CarRegistryApiBundle:Plate p
			JOIN p.car c
			WHERE c.id = :carId
		')->setParameter('carId', $car->getId())
			->getArrayResult();
	}

	public function save(Plate $plate) {
		$this->em->persist($plate);
		$this->em->flush();
	}

	public function getOne(Car $car, $plateId) {
		$results = $this->em->createQuery('
			SELECT p FROM CarRegistryApiBundle:Plate p
			JOIN p.car c
			WHERE p.id = :plateId
			AND c.id = :carId
		')->setParameter('carId', $car->getId())
			->setParameter('plateId', $plateId)
			->getArrayResult();

		if (!count($results)) throw new NoResultException();

		return $results[0];
	}

	public function getOneEntity(Car $car, $plateId) {
		$car = $this->em->createQuery('
			SELECT p FROM CarRegistryApiBundle:Plate p
			JOIN p.car c
			WHERE p.id = :plateId
			AND c.id = :carId
		')->setParameter('carId', $car->getId())
			->setParameter('plateId', $plateId)
			->getSingleResult();

		return $car;
	}

	public function delete(Car $car, $plateId) {
		$this->em->remove($this->getOneEntity($car, $plateId));
		$this->em->flush();
	}

}
