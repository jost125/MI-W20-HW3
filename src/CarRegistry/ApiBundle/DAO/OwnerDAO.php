<?php

namespace CarRegistry\ApiBundle\DAO;

class OwnerDAO {

	public function getAll() {
		return array(
			array(
				'id' => 1,
				'name' => 'John Doe',
			),
			array(
				'id' => 2,
				'name' => 'Arthur Dent',
			)
		);
	}

}
