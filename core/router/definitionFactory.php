<?php

/**
 * 
 * A factory to create Definition objects.
 * 
 * 
 */
class DefinitionFactory {

    /**
     * 
     * Returns a new Definition instance.
     * 
     * @param string $type The type of definition, 'single' or 'attach'.
     * 
     * @param array|callable $spec The definition spec: either an array, or a
     * callable that returns an array.
     * 
     * @param string $path_prefix For 'attach' definitions, use this as the 
     * prefix for attached paths.
     * 
     * @return Route
     * 
     */
    public function newInstance($type, $spec, $path_prefix = null) {
        return new Definition($type, $spec, $path_prefix);
    }

}
