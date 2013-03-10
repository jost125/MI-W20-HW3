<?php

namespace CarRegistry\ApiBundle\DAO;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use CarRegistry\ApiBundle\Entity\Owner;

class OwnerDAO {

	private $em;

	public function __construct(EntityManager $em) {
		$this->em = $em;
	}

	public function getAll() {
		return $this->em->createQuery('SELECT o FROM CarRegistryApiBundle:Owner o')->getArrayResult();
	}

	public function save(Owner $owner) {
		$this->em->persist($owner);
		$this->em->flush();
	}

	public function getOne($id) {
		$results =  $this->em->createQuery('SELECT o FROM CarRegistryApiBundle:Owner o WHERE o.id = :id')
			->setParameter('id', $id)
			->getArrayResult();

		if (!count($results)) throw new NoResultException();

		return $results[0];
	}

	public function getOneEntity($id) {
		$owner = $this->em->find('CarRegistryApiBundle:Owner', $id);
		if (!$owner) {
			throw new NoResultException();
		}
		return $owner;
	}

	public function delete($id) {
		$this->em->remove($this->getOneEntity($id));
		$this->em->flush();
	}

}
