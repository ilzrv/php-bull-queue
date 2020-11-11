<?php

declare(strict_types=1);

namespace Ilzrv\PhpBullQueue\DTOs;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;

abstract class DataTransferObject
{
    public function __construct(array $parameters = [])
    {
        $class = new ReflectionClass(static::class);

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $property = $reflectionProperty->getName();

            if (isset($parameters[$property])) {
                $this->{$property} = $parameters[$property];
            } else {
                /** @var ReflectionNamedType $type */
                $type = $reflectionProperty->getType();
                $name = $type->getName();

                $this->{$property} = !$type->isBuiltin() && class_exists($name)
                    ? new $name()
                    : $this->{$property} ?? null;
            }

//            $this->{$property} = !$type->isBuiltin() && class_exists($name)
//                ? $parameters[$property] ?? new $name()
//                : $parameters[$property] ?? $this->{$property} ?? null;
        }
    }

    public function toJson(): string
    {
        return (string) json_encode($this);
    }
}
