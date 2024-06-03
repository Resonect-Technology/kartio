<?php

namespace App\Document;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\Types\Type;

/*
    Brand
    
    This class represents business owners' brands.
*/

#[ODM\Document()]
class Brand
{
    #[ODM\Id()]
    private $id;

    #[ODM\Field(type: Type::STRING)]
    private string $name;

    #[ODM\EmbedMany(targetDocument: LoyaltyCard::class)]
    private Collection $loyaltyCards;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->loyaltyCards = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLoyaltyCards(): Collection
    {
        return $this->loyaltyCards;
    }

    public function addLoyaltyCard(LoyaltyCard $loyaltyCard): void
    {
        $this->loyaltyCards->add($loyaltyCard);
    }
}
