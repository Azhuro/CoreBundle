<?php
/**
 * (c) Johnny Cottereau <johnny.cottereau@gmail.com>
 */

namespace CoreBundle\Model\Interfaces;

interface TimestampableInterface
{
    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt = null);

    /**
     * @return \DateTime
     */
    public function getUpdatedAt();

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt = null);
}