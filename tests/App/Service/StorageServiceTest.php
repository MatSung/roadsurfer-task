<?php

namespace App\Tests\App\Service;

use App\Service\StorageService;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StorageServiceTest extends KernelTestCase
{

    private StorageService $storageService;

    protected function setUp(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $this->storageService = $container->get('App\Service\StorageService');
    }

    public function testRequestCollection(): void
    {
        $request = file_get_contents('request.json');

        $this->storageService->collect($request);

        $this->storageService->getCollection('vegetable');

        $this->assertNotEmpty($this->storageService->getRequest());
        $this->assertIsString($this->storageService->getRequest());

    }

    public function testValidItem(): void
    {
        $request = '[{
            "id": 1,
            "name": "Carrot",
            "type": "vegetable",
            "quantity": 10922,
            "unit": "g"
          }]';

        $this->storageService->collect($request);

        $this->expectNotToPerformAssertions();
    }

    public function testValidItemClass(): void
    {
        $request = '[{
            "id": 1,
            "name": "Carrot",
            "type": "vegetable",
            "quantity": 10922,
            "unit": "g"
          }]';
              
        $this->storageService->collect($request);
    
        $this->assertInstanceOf('App\Item\Vegetable', $this->storageService->getCollection('vegetable')->get('carrot'));
    }

    public function testValidatorInvalidItem(): void
    {
        $request = '[{
            "id": 1,
            "name": "Carrot",
            "type": "vegetable",
            "quantity": -10922,
            "unit": "g"
          }]';

          $this->expectException(\Exception::class);
          $this->storageService->collect($request);
    }

    public function testDesearializeInvalidItemType(): void
    {
        $request = '[{
            "id": 1,
            "name": "Carrot",
            "type": "nut",
            "quantity": 10922,
            "unit": "g"
          }]';

        $this->expectException(\Exception::class);
        $this->storageService->collect($request);
    }

}
