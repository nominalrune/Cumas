<?php

namespace App\Service\Log;

class Logger
{
	private string $logDir = "./log";
	private string $path = $this->logDir . "/" . date("Y-m-d") . ".log";
	private string $time = "[" . date("Y-m-d h:i:s") . "] ";
	private function save(string $message) : void
	{
		if (! file_exists($this->logDir)) {
			mkdir($this->logDir);
		}
		if (! file_exists($this->path)) {
			touch($this->path);
		}
		$result = file_put_contents($this->path, $message . PHP_EOL, FILE_APPEND);
		if (! $result) {
			throw new \Exception("Failed to save $this->path");
		}
	}
	public function log(string $message) : void
	{
		$this->save($this->time . $message);
		
	}
	public function info(string $message): void
	{
		$this->save($this->time . "[INFO] " . $message);
	}
	public function error(\Exception|string $e): void
	{
		if(is_string($e)) {
			$this->save($this->time . "[ERROR] " . $e);
			return;
		}
		$this->save(
			$this->time . "[ERROR] " .
			$e->getFile() . 'line:' . $e->getLine() . PHP_EOL .
			$e->getMessage() . PHP_EOL .
			$e->getTraceAsString() . PHP_EOL
		);
	}
}