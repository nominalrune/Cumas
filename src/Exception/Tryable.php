<?php

// namespace App\Exception;

// trait Tryable{
// 	protected function try(callable $func)
// 	{
// 		return (object)[
// 			"catch"=>function (callable $catchFunc)use($func)
// 			{
// 				try {
// 					$this->func();
// 				} catch (\Exception $e) {
// 					$catchFunc($e);
// 				}
// 			}
// 		]
// 	}
// }