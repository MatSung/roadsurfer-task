<?php

namespace App\Service;

use App\Collection\ItemCollection;
use App\Item\AbstractItem;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StorageService
{

    const STORAGE_UNIT = 'g';

    /**
     * array to store the collections, in a real world scenario this would be stored in a database or similar
     * @var array
     */
    private array $collections = [];

    /**
     * The request string
     * @var string
     */
    protected string $request = '';
    

    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {
    }

    /**
     * Get the request string
     * @return string
     */
    public function getRequest(): string
    {
        return $this->request;
    }

    /**
     * Get all collections
     * @return array
     */
    public function getCollections(): array
    {
        return $this->collections;
    }

    /**
     * Get a specific collection
     * @param string $type
     * @return ItemCollection
     */
    public function getCollection(string $type): ItemCollection
    {
        return $this->collections[$type];
    }

    /**
     * Collect items into collections from a json request string
     * @param string $request
     * @return self
     */
    public function collect(string $request): self
    {
        $this->request = $request;

        $serialized = $this->deserializeRequest();

        foreach ($serialized as $key => $item) {
            $this->validateItem($item);

            $type = $item->getType();

            if (!isset($this->collections[$type])) {
                $this->collections[$type] = new ItemCollection($type);
            }

            $this->collections[$type]->add($item);

        }
        return $this;
    }

    /** 
     * Deserialize the request string into item objects
     * @return array
     */
    protected function deserializeRequest(): array
    {
        return $this->serializer->deserialize($this->request, 'App\Item\AbstractItem[]', 'json');
    }

    /**
     * Validate an item
     * @param AbstractItem $item
     * @return void
     * @throws \Exception on invalid item
     */
    protected function validateItem(AbstractItem $item): void
    {
        $errors = $this->validator->validate($item);
        if (count($errors) > 0) {
            throw new \Exception((string) $errors);
        }
    }

}
