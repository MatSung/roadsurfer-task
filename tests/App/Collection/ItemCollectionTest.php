<?php

namespace App\Tests\App\Collection;

use App\Collection\ItemCollection;
use PHPUnit\Framework\TestCase;

class ItemCollectionTest extends TestCase
{
    public function testAddItemsToCollection(): void
    {
        $collection = new ItemCollection('fruit');
        $collection->add(new \App\Item\Fruit('apple', 1, 'kg'));
        $collection->add(new \App\Item\Fruit('banana', 2, 'kg'));

        $this->assertCount(2, $collection->getItems());
    }

    public function testAddSameItemToCollection(): void
    {
        $collection = new ItemCollection('fruit');
        $collection->add(new \App\Item\Fruit('apple', 2, 'g'));
        $collection->add(new \App\Item\Fruit('apple', 13, 'g'));

        $this->assertCount(1, $collection->getItems());
        $this->assertEquals(15, $collection->get('apple')->getQuantity());
    }

    public function testRemoveItemFromCollection(): void
    {
        $collection = new ItemCollection();
        $collection->add(new \App\Item\Fruit('apple', 1, 'g'));
        $collection->remove('apple');

        $this->assertCount(0, $collection->getItems());
    }

    public function testGetType(): void
    {
        $collection = new ItemCollection('fruit');

        $this->assertEquals('fruit', $collection->getType());
    }

    public function testAddItemToCollectionWithInvalidType(): void
    {
        $collection = new ItemCollection('fruit');

        $this->expectException(\InvalidArgumentException::class);
        $collection->add(new \App\Item\Vegetable('carrot', 1, 'g'));
    }

    public function testAddItemAndConvertWeight(): void
    {
        $collection = new ItemCollection('fruit');

        $collection->add(new \App\Item\Fruit('apple', 1, 'kg'));
        $this->assertEquals(1000, $collection->get('apple')->getQuantity('g'));
    }

}