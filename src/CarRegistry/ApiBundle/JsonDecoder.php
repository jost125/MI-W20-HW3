<?php

namespace CarRegistry\ApiBundle;

class JsonDecoder {

	public function decodeAndFill($jsonString, $entity) {
		$json = json_decode($jsonString, true);
		foreach ($json as $propertyName => $propertyValue) {
			$entity->{'set' . ucfirst($propertyName)}($propertyValue);
		}
	}

}
