<?php

namespace Workana\Tests\Domain\Model;

use ReflectionClass;

class Mother
{
    protected static function setProperties(ReflectionClass $reflectionClass, $aInstance, array $data): void
    {
        foreach ($reflectionClass->getProperties() as $property) {
            if (true === isset($data[$property->getName()])) {
                $property->setAccessible(true);
                $property->setValue($aInstance, $data[$property->getName()]);
            }
        }
    }
}
