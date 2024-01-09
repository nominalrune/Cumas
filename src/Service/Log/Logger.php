<?php

namespace App\Service\Log;

class Logger
{
	private string $logDir = "./log";
	public function log(string $message) : void
	{
		$path = $this->logDir . "/" . date("Y-m-d") . ".log";
		$prefix = "[" . date("Y-m-d h:i:s") . "] ";
		$result = file_put_contents($path, $prefix . $message . PHP_EOL, FILE_APPEND);
		if (! $result) {
			throw new \Exception("Failed to save $path");
		}
	}
}