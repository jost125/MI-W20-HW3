<?php

namespace CarRegistry\ApiBundle\DAO;

use Doctrine\ORM\EntityManager;
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

}
