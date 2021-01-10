<?php


namespace App\Graph;

/**
 * Interface GEdgeInterface
 * @package App\Entity
 */
interface GEdgeInterface
{
    /**
     * @return string|null
     */
    public function GEdgeName(): ?string;

    /**
     * @return GNodeType|null
     */
    public function GEdgeType(): ?string;

    /**
     * @return array|null
     */
    public function GMeta(): ?array;

    public function GFrom(): ?GNodeInterface;

    public function GTo(): ?GNodeInterface;
}
