<?php

namespace CarRegistry\ApiBundle\ExceptionHandler;

use JMS\AopBundle\Aop\PointcutInterface;
use ReflectionClass;
use ReflectionMethod;

class AllControllerPointcut implements PointcutInterface {

	public function matchesClass(ReflectionClass $class) {
		return $class->getNamespaceName() === 'CarRegistry\ApiBundle\Controller';
	}

	public function matchesMethod(ReflectionMethod $method) {
		return preg_match('~Action$~', $method->getName());
	}
}
