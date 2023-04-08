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
            $this->set($id, $this->resolve($id));
        }

        return $this->instances[$id];
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $id Identifier of the entry to look for.
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->instances[$id]);
    }

    /**
     * Adds an instance to the container.
     *
     * @param string $id Identifier of the entry.
     * @param mixed $instance The instance to add.
     * @return void
     */
    public function set(string $id, mixed $instance): void
    {
        $this->instances[$id] = $instance;
    }

    /**
     * Resolves a class by creating an instance of it and resolving its dependencies recursively.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws ContainerException Error while retrieving the entry.
     * @throws ReflectionException If the class or constructor does not exist.
     *
     * @return ?object An instance of the resolved class.
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
