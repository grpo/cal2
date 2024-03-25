<?php declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\Exception\NotSupported;

/**
 *  Maps entity to updated entity by corresponding setter methods.
 *  If updated property has value and setter method, entity is updated with new value
 */
class EntityUpdaterService
{

    public const APP_ENTITY = 'App\\Entity';
    
    public function update($existingEntity, $updatedEntity)
    {
        $this->checkAppEntityNamespace($existingEntity);
        $this->checkAppEntityNamespace($updatedEntity);

        $ref = new \ReflectionClass($updatedEntity);
        $props = $ref->getProperties();
        foreach ($props as $prop) {
            $set = 'set'.ucfirst($prop->getName()); // setId
            $get = 'get'.ucfirst($prop->getName());
            if ($updatedEntity->$get() !== null && $ref->getMethod($set) !== null) {
                $existingEntity->$set($updatedEntity->$get());
            }
        }

        return $existingEntity;
    }

    private function checkAppEntityNamespace($obj): void
    {
        $reflection = new \ReflectionClass($obj);
        $namespace = $reflection->getNamespaceName();
        if ( $namespace !== self::APP_ENTITY ) {
            throw new NotSupported();
        }
    }
}