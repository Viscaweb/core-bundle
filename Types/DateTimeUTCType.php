<?php

namespace Visca\Bundle\CoreBundle\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;

/**
 * This aim of this type is to do exactly what's doing DateTimeType.
 * The only difference when converting the raw data to PHP object.
 *
 * Basically, the DateTimeType is creating a \DateTime object from the provided
 * string date and it does NOT specify any specific \DateTimeZone.
 * Therefore, PHP will take the default application timezone.
 *
 * Because our dates are all saved in UTC, this type will do the same but
 * it will force the \DateTime objects to be created as UTC.
 *
 * Class DateTimeUTCType
 */
class DateTimeUTCType extends DateTimeType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'DateTimeUTCType';
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof \DateTime) {
            return $value;
        }

        $val = \DateTime::createFromFormat(
            $platform->getDateTimeTzFormatString(),
            $value,
            new \DateTimeZone('UTC')
        );
        if (!$val) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeTzFormatString()
            );
        }

        return $val;
    }
}
