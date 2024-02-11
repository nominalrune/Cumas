<?php

namespace App\Exception;

class ChainedException extends \Exception
{
	public function __construct(
		\Exception $error,
		protected $message = '',
		private string $class = '',
		private string $method,
	) {
		$msg = $this->message . PHP_EOL .
			'In ' . $this->class . '::' . $this->method . PHP_EOL;
		parent::__construct($error->getMessage() . $msg, $error->getCode(), $error);
	}
}