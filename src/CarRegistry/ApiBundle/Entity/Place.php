<?php

namespace CarRegistry\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Place {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", unique=true)
	 */
	private $address;

	/**
	 * @var Owner
	 * @ORM\ManyToOne(targetEntity="Owner")
	 */
	private $owner;

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setAddress($address) {
		$this->address = $address;
	}

	public function getAddress() {
		return $this->address;
	}

	public function setOwner(Owner $owner) {
		$this->owner = $owner;
	}

}
