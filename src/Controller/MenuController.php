<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MenuService;

#[Route('/api/v1/menu', name:'menu')]
class MenuController extends AbstractController
{

    private MenuService $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    // affiche tous les menus
    #[Route('/', name:'get_all_menu', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        return new JsonResponse($this->menuService->getAll());
    }

    // affiche un menu
    #[Route('/{id}', name:'get_menu', methods: ['GET'])]
    public function getMenu(int $id): JsonResponse
    {
        return new JsonResponse($this->menuService->getMenu($id));
    }

    // add menu
    #[Route('/', name:'add_menu', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $jsonString = $request->getContent();

        // Décoder la chaîne JSON en un tableau associatif PHP
        $data = json_decode($jsonString, true);

        return new JsonResponse($this->menuService->add(
            $data['name'],
            $data['description'],
            $data['plats'],
            $data['prix'],
            $data['user_id'],
            $data['public']
        ));
    }

    // update menu
    #[Route('/{id}', name:'update_menu', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $jsonString = $request->getContent();

        // Décoder la chaîne JSON en un tableau associatif PHP
        $data = json_decode($jsonString, true);

        $name = $data['name'] ?? null;
        $description = $data['description'] ?? null;
        $plats = $data['plats'] ?? null;
        $prix = $data['prix'] ?? null;
        $user_id = $data['user_id'] ?? null;
        $public = $data['public'] ?? null;

        return new JsonResponse($this->menuService->update(
            $id,
            $name,
            $description,
            $plats,
            $prix,
            $user_id,
            $public
        ));
    }

    // delete menu
    #[Route('/{id}', name:'delete_menu', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return new JsonResponse($this->menuService->delete($id));
    }

    // get menu by user
    #[Route('/user/{id}', name:'get_menu_by_user', methods: ['GET'])]
    public function getMenuByUser(int $id): JsonResponse
    {
        return new JsonResponse($this->menuService->getMenuByUser($id));
    }

    // get menu by public
    #[Route('/public', name:'get_menu_by_public', methods: ['POST'])]
    public function getMenuByPublic(): JsonResponse
    {
        return new JsonResponse($this->menuService->getMenuByPublic());
    }

    // get menu by name
    #[Route('/name', name:'get_menu_by_name', methods: ['POST'])]
    public function getMenuByName(Request $request): JsonResponse
    {
        $jsonString = $request->getContent();

        // Décoder la chaîne JSON en un tableau associatif PHP
        $data = json_decode($jsonString, true);

        return new JsonResponse($this->menuService->getMenuByName($data['name']));
    }

    // get menu by plat
    #[Route('/plat/{id}', name:'get_menu_by_plat', methods: ['GET'])]
    public function getMenuByPlat(int $id): JsonResponse
    {
        return new JsonResponse($this->menuService->getMenuByPlat($id));
    }

    // get menu by prix
    #[Route('/prixMax', name:'get_menu_by_prix', methods: ['POST'])]

    public function getMenuByPrixMax(Request $request): JsonResponse
    {
        $jsonString = $request->getContent();

        // Décoder la chaîne JSON en un tableau associatif PHP
        $data = json_decode($jsonString, true);

        return new JsonResponse($this->menuService->getMenuByPrixMax($data['prix']));
    }
}
