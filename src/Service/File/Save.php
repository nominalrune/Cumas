<?php

namespace App\Service\File;

trait Save
{
	public function save(string $filename, string $content) : string
	{
		$path = $filename;
		$result = file_put_contents($path, $content);
		if (! $result) {
			throw new \Exception("Failed to save $path");
		}
		return $path;
	}
}