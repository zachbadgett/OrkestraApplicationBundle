<?php

namespace Orkestra\Bundle\ApplicationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Orkestra\Common\Type\Date;
use Orkestra\Common\Type\DateTime;
use Doctrine\DBAL\Types\Type;

class OrkestraApplicationBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function boot()
    {
        Type::overrideType('datetime', 'Orkestra\Common\DBAL\Types\DateTimeType');
        Type::overrideType('date', 'Orkestra\Common\DBAL\Types\DateType');
        Type::addType('encrypted_string', 'Orkestra\Common\DBAL\Types\EncryptedStringType');

        $encryptedStringType = Type::getType('encrypted_string');
        $encryptedStringType->setKey($this->container->getParameter('secret'));

        // Set up date related settings
        $defaultTimezone = new \DateTimeZone(date_default_timezone_get());
        DateTime::setServerTimezone($defaultTimezone);
        DateTime::setUserTimezone($defaultTimezone);
        DateTime::setDefaultFormat('Y-m-d H:i:s');
        Date::setDefaultFormat('Y-m-d');
    }
}
