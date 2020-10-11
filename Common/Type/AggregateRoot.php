<?php
declare(strict_types=1);

namespace Common\Type;

use DateTime;
use DateTimeImmutable;

abstract class AggregateRoot
{
    protected DateTimeImmutable $createdAt;
    protected Datetime $updatedAt;

    public function __construct(DateTimeImmutable $createdAt, DateTime $updatedAt)
    {
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    abstract public function getId(): Id;

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}
