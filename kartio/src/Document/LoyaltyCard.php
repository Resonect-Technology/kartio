<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\Types\Type;

#[ODM\EmbeddedDocument()]
class LoyaltyCard
{
    #[ODM\Field(type: Type::STRING)]
    private string $customerName;

    #[ODM\Field(type: Type::STRING)]
    private ?string $email = null;

    #[ODM\Field(type: Type::STRING)]
    private string $cardIdentifier;

    #[ODM\Field(type: Type::STRING)]
    private ?string $phoneNumber = null;

    #[ODM\ReferenceOne(targetDocument: Brand::class)]
    private ?Brand $brand = null;

    public function __construct(string $customerName, string $email, string $phoneNumber, string $cardIdentifier)
    {
        $this->customerName = $customerName;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setCustomerName(string $customerName): void
    {
        $this->customerName = $customerName;
    }

    public function setCardIdentifier(string $cardIdentifier): void
    {
        $this->cardIdentifier = $cardIdentifier;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(Brand $brand): void
    {
        $this->brand = $brand;
    }
}
