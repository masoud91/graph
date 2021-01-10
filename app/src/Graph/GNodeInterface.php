<?php


namespace App\Graph;

/**
 * Interface GNodeInterface
 * @package App\Entity
 */
interface GNodeInterface
{
    /**
     * @return string|null
     */
    public function GNodeName(): ?string;

    /**
     * @return GNodeType|null
     */
    public function GNodeTypeName(): ?string;

    /**
     * @return array|null
     */
    public function GMeta(): ?array;

    /**
     * @return int
     */
    public function GResourceKey(): int;
}
