<?php

namespace CarRegistry\ApiBundle\DAO;

use Doctrine\ORM\EntityManager;

class OwnerDAO {

	private $em;

	public function __construct(EntityManager $em) {
		$this->em = $em;
	}

	public function getAll() {
		return $this->em->createQuery('SELECT o FROM CarRegistryApiBundle:Owner o')->getArrayResult();
	}

}
