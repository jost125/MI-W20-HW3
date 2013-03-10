<?php

namespace CarRegistry\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Plate {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", unique=true)
	 */
	private $number;

	/**
	 * @var Car
	 * @ORM\ManyToOne(targetEntity="Car")
	 */
	private $car;

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setNumber($number) {
		$this->number = $number;
	}

	public function getNumber() {
		return $this->number;
	}

	public function setCar(Car $car) {
		$this->car = $car;
	}

}
