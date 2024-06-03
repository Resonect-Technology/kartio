<?php

namespace App\Document;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\Types\Type;

/*
    Brand
    
    This class represents business owners' brands.
*/

#[MongoDB\Document(collection: "brands")]
class Brand
{
    #[ODM\Id()]
    public $id;

    #[ODM\Field(type: Type::STRING)]
    public string $name;

    #[ODM\Field(type: Type::STRING)]
    public string $slug;

    #[ODM\EmbedMany(targetDocument: Availability::class)]
    public Collection $managers;

    public function __construct()
    {
        $this->managers = new ArrayCollection();
    }
}
