<?php

namespace App\Service\File;

trait Save
{
	public function __construct(
		private string $saveDir
	) {
	}
	public function save(string $filename, string $content) : string
	{
		$path = $this->saveDir . '/' . $filename;
		$result = file_put_contents($path, $content);
		if (! $result) {
			throw new \Exception("Failed to save $path");
		}
		return $path;
	}
}