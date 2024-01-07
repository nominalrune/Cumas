<?php
namespace App\Doctrine\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use App\Service\String\Crypt;

class EncryptedStringType extends Type
{
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getClobTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
		$crypt = new Crypt();
        return $crypt->decrypt($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $crypt = new Crypt();
        return $crypt->encrypt($value);
    }

    public function getName()
    {
        return 'encrypted_string';
    }
}
