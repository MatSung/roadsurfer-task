<?php

namespace App\Collection;

use App\Item\AbstractItem;

class ItemCollection
{

    /**
     * array to store the items
     * @var array
     */
    protected array $items = [];

    public function __construct(
        protected ?string $type = null
    ) {
    }

    /**
     * Get the collection type
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get all items in the collection
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Add an item to the collection
     * @param AbstractItem $item
     * @return self
     */
    public function add(AbstractItem $item): self
    {
        if($this->type !== null && $item->getType() !== $this->type) {
            throw new \InvalidArgumentException('Item type does not match collection type');
        }

        $name = $item->getName();

        if (isset($this->items[$name])) {
            $this->items[$name]->setQuantity($this->items[$name]->getQuantity() + $item->getQuantity());
        } else {
            $this->items[$name] = $item;
        }
        return $this;
    }

    /**
     * Remove an item from the collection
     * @param string $name
     * @return ?AbstractItem The removed item (or null)
     */
    public function remove(string $name): ?AbstractItem
    {
        if (!isset($this->items[$name])) {
            return null;
        }
        $item = $this->items[$name];
        unset($this->items[$name]);
        return $item;
    }

    /**
     * Get an item from the collection
     * @param string $name
     * @return ?AbstractItem The item (or null)
     */
    public function get(string $name): ?AbstractItem
    {
        return $this->items[$name] ?? null;
    }

    /**
     * List all items in the collection
     * @return array
     */
    public function list(bool $convertToKg = false): array
    {
        $result = $this->items;

        $unit = $convertToKg ? 'kg' : 'g';

        foreach ($result as $key => &$value) {
            $value = $value->getQuantity($unit) . ' ' . $unit . ' of ' . $value->getName();
        }

        return $result;
    }

}
