<?php

namespace App\Service\Log;

class Logger
{
	private string $logDir;
	private string $path;
	private string $time;
	public function __construct()
	{
		if(!isset($_ENV['STORAGE_PATH'])) throw new \Exception("STORAGE_PATH is not set.");
		$this->logDir = $_ENV['STORAGE_PATH'] . "/log";
		$this->path = $this->logDir . "/" . date("Y-m-d") . ".log";
		$this->time = "[" . date("Y-m-d h:i:s") . "] ";
	}
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
	public function info(string $message) : void
	{
		$this->save($this->time . "[INFO] " . $message);
	}
	public function error(\Exception|string $e) : void
	{
		if (is_string($e)) {
			$this->save($this->time . "[ERROR] " . $e);
			return;
		}
		$this->save(
			$this->time . "[ERROR] " .
			'\t' . $e->getMessage() . PHP_EOL .
			'\t' . 'In ' . $e->getFile() . '(line:' . $e->getLine() . ')' . PHP_EOL .
			'\t' . 'stuck trace:' . PHP_EOL .
			'\t' . join(array_map(fn ($arr) => (array_map(fn ($key) => (is_array($arr[$key]) ? ("$key: " . join($arr[$key], ",")) : "$key: $arr[$key]"), array_keys($arr))), $e->getTrace()), PHP_EOL . '\t') . PHP_EOL
		);
	}
}