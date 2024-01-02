<?php

namespace App\Service\String;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
class Crypt
{
	private $algo = 'sha256';
	private $cipher = 'aes-128-cbc';
	private $as_binary = true;
	private $sha2len = 32;
	private int $ivlen;
	private int $options = OPENSSL_RAW_DATA;
	private string $key;
	public function __construct()
	{
		$this->key = $_ENV['APP_SECRET'];
		$this->ivlen = openssl_cipher_iv_length($this->cipher);
	}
	public function encrypt(string $plaintext) : string
	{
		$iv = openssl_random_pseudo_bytes($this->ivlen);
		$ciphertext_raw = openssl_encrypt($plaintext, $this->cipher, $this->key, $this->options, $iv);
		$hmac = hash_hmac($this->algo, $ciphertext_raw, $this->key, $this->as_binary);
		$ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);
		return $ciphertext;
	}
	public function decrypt(string $ciphertext) : string
	{
		$c = base64_decode($ciphertext);
		$iv = substr($c, 0, $this->ivlen);
		$hmac = substr($c, $this->ivlen, $this->sha2len);
		$ciphertext_raw = substr($c, $this->ivlen + $this->sha2len);
		$original_plaintext = openssl_decrypt($ciphertext_raw, $this->cipher, $this->key, $this->options, $iv);
		$calcmac = hash_hmac($this->algo, $ciphertext_raw, $this->key, $this->as_binary);
		if (!hash_equals($hmac, $calcmac)) {
			throw new \Exception('Encryption failed');
		}
		return $original_plaintext;
	}
}