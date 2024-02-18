<?php

namespace App\Item;

use App\Service\StorageService;
use App\Util\ConversionUtil;
use App\Item\Fruit;
use App\Item\Vegetable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

#[DiscriminatorMap(typeProperty: 'type', mapping: [
    'fruit' => Fruit::class,
    'vegetable' => Vegetable::class,
])]
abstract class AbstractItem
{

    #[Assert\NotBlank]
    protected ?string $type = null;

    #[Assert\NotBlank]
    protected string $name;

    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    protected float $quantity;

    #[Assert\NotBlank]
    protected string $unit = StorageService::STORAGE_UNIT;

    public function __construct(
        string $name,
        float $quantity,
        string $unit = 'g'
    ) {
        $this->name = strtolower($name);
        $this->quantity = ConversionUtil::convertWeight($quantity, $unit, $this->unit);
    }

    public function getQuantity(string $unit = 'g'): float
    {
        return ConversionUtil::convertWeight($this->quantity, $this->unit, $unit);
    }

    public function setQuantity(float $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function __toString(): string
    {
        return sprintf('%s %s', $this->quantity, $this->unit);
    }

}
