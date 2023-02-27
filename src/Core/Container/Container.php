<?php

namespace Horus\Core\Container;

use ReflectionClass;
use ReflectionException;

class Container implements ContainerInterface
{
    private array $instances = [];

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws ContainerException Error while retrieving the entry.
     * @throws ReflectionException If the class or constructor does not exist.
     *
     * @return mixed Entry.
     */
    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            $instance = $this->resolve($id);
            $this->instances[$id] = $instance;
        }

        return $this->instances[$id];
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->instances[$id]);
    }

    /**
     * Resolves a class by creating an instance of it and resolving its dependencies recursively.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws ContainerException Error while retrieving the entry.
     * @throws ReflectionException If the class or constructor does not exist.
     *
     * @return object | null An instance of the resolved class.
     */
    public function resolve(string $id): ?object
    {
        $reflector = new ReflectionClass($id);
        if (!$reflector->isInstantiable()) {
            throw new ContainerException("Class {$id} not instantiable");
        }

        $constructor = $reflector->getConstructor();
        if (!$constructor) {
            return $reflector->newInstance();
        }

        $args = [];
        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();
            if (!$type) {
                if (!$parameter->isDefaultValueAvailable()) {
                    throw new ContainerException("Could not resolve class {$id} dependency {$parameter->getName()}");
                }

                $args[] = $parameter->getDefaultValue();
            } else {
                $args[] = $this->get($type->getName());
            }
        }

        return $reflector->newInstanceArgs($args);
    }
}
