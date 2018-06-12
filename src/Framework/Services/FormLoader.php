<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 05.04.2018
 * Time: 20:04
 */
declare(strict_types=1);

namespace Framework\Services;

use ReflectionClass;

/**
 * Class FormLoader
 * @package Framework\Services
 */
class FormLoader
{
    /**
     * @param array $formData
     * @param $object
     * @return mixed
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function load(array $formData, $object, $only = [])
    {
        if (is_string($object) && class_exists($object)) {
            if (!class_exists($object)) {
                throw new \Exception('class not exists ' . $object);
            }
            $object = new $object();
        }
        $reflection = new ReflectionClass($object);
        $properties = $reflection->getProperties();
        $public = array_filter($properties, function (\ReflectionProperty $property) {
            return $property->isPublic();
        });
        foreach ($public as $property) {
            $propertyName = $property->getName();
            if (count($only) > 0) {
                if (!in_array($propertyName, $only)) {
                    continue;
                }
            }
            $value = $formData[$propertyName] ?? null;
            $value = is_null($value) ? null : trim($value);
            $property->setValue($object, $value);
        }
        return $object;
    }
}
