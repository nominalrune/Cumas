<?php

namespace App\Service\File;

trait Savable
{
	/** 
	 * @param string $filename fullpath to file
	 * @param string $content content to save
	 * @return string $path
	 */
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