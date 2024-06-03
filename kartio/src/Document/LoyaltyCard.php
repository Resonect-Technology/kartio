<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\Types\Type;

class LoyaltyCard
{
    #[ODM\Field(type: Type::STRING)]
    private string $customerName;

    #[ODM\Field(type: Type::STRING)]
    private string $cardIdentifier;

    public function __construct(string $customerName, string $cardIdentifier)
    {
        $this->customerName = $customerName;
        $this->cardIdentifier = $cardIdentifier;
    }

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function getCardIdentifier(): string
    {
        return $this->cardIdentifier;
    }
}
