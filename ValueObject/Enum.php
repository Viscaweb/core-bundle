<?php

namespace Visca\Bundle\CoreBundle\ValueObject;

use UnexpectedValueException;

/**
 * Class Enum.
 *
 * Inspired from:
 *  - https://github.com/garoevans/php-enum
 *  - https://github.com/myclabs/php-enum
 */
abstract class Enum
{
    /**
     * Store existing constants in a static cache per object.
     *
     * @var array
     */
    private static $cache = [];

    /**
     * Enum value.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Creates a new value of some type.
     *
     * @param mixed $value
     *
     * @throws UnexpectedValueException if incompatible type is given.
     */
    final public function __construct($value)
    {
        if (!$this->isValid($value)) {
            throw new UnexpectedValueException(
                sprintf(
                    'Value "%s" is not part of the enum "%s"',
                    $value,
                    get_called_class()
                )
            );
        }
        $this->value = $value;
    }

    /**
     * Check if is valid enum value.
     *
     * @param $value
     *
     * @return bool
     */
    private static function isValid($value)
    {
        return in_array($value, self::toArray(), true);
    }

    /**
     * Returns all possible values as an array.
     *
     * @return array Constant name in key, constant value in value
     */
    private static function toArray()
    {
        $class = get_called_class();
        if (!array_key_exists($class, self::$cache)) {
            $reflection = new \ReflectionClass($class);
            self::$cache[$class] = $reflection->getConstants();
        }

        return self::$cache[$class];
    }

    /**
     * Return key for value.
     *
     * @param $value
     *
     * @return mixed
     */
    public static function search($value)
    {
        return array_search($value, self::toArray(), true);
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public static function fromValue($value)
    {
        $const = static::constFromValue($value);

        return static::$const();
    }

    /**
     * @param $value
     *
     * @return mixed
     *
     * @throws \UnexpectedValueException
     */
    public static function constFromValue($value)
    {
        $const = array_search($value, self::values());
        if ($const === false) {
            throw new \UnexpectedValueException(
                "Value '{$value}' does not exist"
            );
        }

        return $const;
    }

    /**
     * Returns instances of the Enum class of all Enum constants.
     *
     * @return array Constant name in key, Enum instance in value
     */
    public static function values()
    {
        $values = [];
        foreach (self::toArray() as $key => $value) {
            $values[$key] = new static($value);
        }

        return $values;
    }

    /**
     * Returns a value when called statically like so: MyEnum::SOME_VALUE() given SOME_VALUE is a class constant.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return static
     *
     * @throws \BadMethodCallException
     */
    public static function __callStatic($name, $arguments)
    {
        if (defined("static::$name")) {
            return new static(constant("static::$name"));
        }
        throw new \BadMethodCallException(
            "No static method or enum constant '$name' in class ".get_called_class(
            )
        );
    }

    /**
     * @param mixed $compare String representation of an enum value, usually
     *                       passed as a constant.
     *
     * @return bool
     */
    public function is($compare)
    {
        return $compare == (string) $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }
}
