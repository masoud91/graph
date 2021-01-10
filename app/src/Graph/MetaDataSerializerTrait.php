<?php


namespace App\Graph;

/**
 * Trait MetaDataSerializerTrait
 * @package App\Graph
 */
trait MetaDataSerializerTrait
{
    /**
     * @param array $fields
     * @return array
     */
    protected function serialize(array $fields) : array
    {
        $result = [];
        foreach ($fields as $field) {
            $result[$field] = $this->$field;
        }

        return $result;
    }
}
