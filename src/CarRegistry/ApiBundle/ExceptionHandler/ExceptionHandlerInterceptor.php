<?php

namespace CarRegistry\ApiBundle\ExceptionHandler;

use CG\Proxy\MethodInterceptorInterface;
use Doctrine\DBAL\DBALException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;
use Doctrine\ORM\NoResultException;
use CG\Proxy\MethodInvocation;

class ExceptionHandlerInterceptor implements MethodInterceptorInterface {

	public function intercept(MethodInvocation $invocation) {
		try {
			$result = $invocation->proceed();
			return $result;
		} catch (NoResultException $ex) {
			return $this->response(array('error' => 'No result found'));
		} catch (DBALException $ex) {
			return $this->response(array('error' => preg_match('~Duplicate entry~', $ex->getMessage()) ? 'Duplicate entry' : 'Unknown error occurred'));
		} catch (Exception $ex) {
			return $this->response(array('error' => 'Unknown error'));
		}
	}

	private function response($responseData) {
		return new JsonResponse(array(
			'response' => $responseData,
		));
	}
}
