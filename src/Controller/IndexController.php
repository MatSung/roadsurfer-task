<?php

// basic symfony index controller
namespace App\Controller;

use App\Service\StorageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(StorageService $storageService): JsonResponse
    {

        $request = file_get_contents('../request.json');
        $result = [];

        // collect items from the request
        $storageService->collect($request);

        // get the vegetable collection
        $vegetableCollection = $storageService->getCollection('vegetable');

        // get an item from the vegetable collection without removing it
        $vegetableCollection->get('carrot');

        // get an item from the vegetable collection and remove it
        $carrot = $vegetableCollection->remove('carrot');

        // change the quantity of the carrot
        $carrot->setQuantity(1000);

        // add it back to the vegetable collection
        $vegetableCollection->add($carrot);

        // list the items in the vegetable collection in kilograms
        $result['list'] = $vegetableCollection->list(true);

        // or programatically get all the items in the collection
        $result['items'] = $vegetableCollection->getItems();

        // or get just one item from the collection
        $result['carrot'] = (string) $vegetableCollection->get('carrot');

        return new JsonResponse($result, 200);
    }
}