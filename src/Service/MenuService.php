<?php

namespace App\Service;

use App\Entity\Menu;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;

class MenuService
{
    private MenuRepository $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(MenuRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function getAll(): array
    {
        $menus = $this->repository->findAll();
        $data = [];
        foreach ($menus as $menu) {
            $data[] = [
                'menu_id' => $menu->getId(),
                'name' => $menu->getName(),
                'plats' => $menu->getPlats(),
                'description' => $menu->getDescription(),
                'prix' => $menu->getPrix(),
                'user_id' => $menu->getUserId(),
                'public' => $menu->isPublic(),
            ];
        }
        return $data;

    }

    public function add(mixed $name, mixed $description, mixed $plats, mixed $prix, mixed $user_id, mixed $public): array
    {
        $menu = new Menu();
        $menu->setName($name);
        $menu->setDescription($description);
        $menu->setPlats($plats);
        $menu->setPrix($prix);
        $menu->setUserId($user_id);
        $menu->setPublic($public);

        $this->entityManager->persist($menu);
        $this->entityManager->flush();

        return [
            'menu_id' => $menu->getId(),
            'name' => $menu->getName(),
            'description' => $menu->getDescription(),
            'plats' => $menu->getPlats(),
            'prix' => $menu->getPrix(),
            'user_id' => $menu->getUserId(),
            'public' => $menu->isPublic(),
        ];
    }

    public function update(int $id, mixed $name, mixed $description, mixed $plats, mixed $prix, mixed $user_id, mixed $public): array
    {
        $menu = $this->repository->find($id);
        if (!$menu) {
            return [
                'status' => 'Menu not found',
            ];
        } else {
            if ($name)
                $menu->setName($name);
            if ($description)
                $menu->setDescription($description);
            if ($plats)
                $menu->setPlats($plats);
            if ($prix)
                $menu->setPrix($prix);
            if ($user_id)
                $menu->setUserId($user_id);
            if ($public !== null)
                $menu->setPublic($public);


            $this->entityManager->flush();

            return [
                'menu_id' => $menu->getId(),
                'name' => $menu->getName(),
                'description' => $menu->getDescription(),
                'plats' => $menu->getPlats(),
                'prix' => $menu->getPrix(),
                'user_id' => $menu->getUserId(),
                'public' => $menu->isPublic(),
            ];
        }
    }

    public function delete(int $id): array
    {
        $menu = $this->repository->find($id);
        if (!$menu) {
            return [
                'status' => 'Menu not found',
            ];
        }
        $this->entityManager->remove($menu);
        $this->entityManager->flush();
        return [
            'status' => 'Menu deleted',
        ];
    }

    public function getMenu(int $id): array
    {
        $menu = $this->repository->find($id);
        if (!$menu) {
            return [
                'status' => 'Menu not found',
            ];
        } else {
            return [
                'menu_id' => $menu->getId(),
                'name' => $menu->getName(),
                'description' => $menu->getDescription(),
                'plats' => $menu->getPlats(),
                'prix' => $menu->getPrix(),
                'user_id' => $menu->getUserId(),
                'public' => $menu->isPublic(),
            ];
        }
    }

    public function getMenuByUser(int $id): array
    {
        $menus = $this->repository->findBy(['user_id' => $id]);
        $data = [];
        foreach ($menus as $menu) {
            $data[] = [
                'menu_id' => $menu->getId(),
                'name' => $menu->getName(),
                'description' => $menu->getDescription(),
                'plats' => $menu->getPlats(),
                'prix' => $menu->getPrix(),
                'user_id' => $menu->getUserId(),
                'public' => $menu->isPublic(),
            ];
        }
        return $data;
    }

    public function getMenuByPublic(): array
    {
        $menus = $this->repository->findBy(['public' => true]);
        $data = [];
        foreach ($menus as $menu) {
            $data[] = [
                'menu_id' => $menu->getId(),
                'name' => $menu->getName(),
                'description' => $menu->getDescription(),
                'plats' => $menu->getPlats(),
                'prix' => $menu->getPrix(),
                'user_id' => $menu->getUserId(),
                'public' => $menu->isPublic(),
            ];
        }
        return $data;
    }

    public function getMenuByName(mixed $name): array
    {
        $menus = $this->getAll();
        $data = [];
        foreach ($menus as $menu) {
            if (str_contains($menu['name'], $name)) {
                $data[] = $menu;
            }
        }
        if (empty($data)) {
            // set the first lettre of the plat to uppercase
            $name = ucfirst($name);
            foreach ($menus as $menu) {
                if (str_contains($menu['name'], $name)) {
                    $data[] = $menu;
                }
            }

        } else {
            return $data;

        }
        if (empty($data)) {
            return [
                'status' => 'Plat not found',
            ];
        } else {
            return $data;
        }
    }

    public function getMenuByPlat(int $id): array
    {
        $menus = $this->getAll();
        $data = [];
        foreach ($menus as $menu) {
            if (in_array($id, $menu['plats'])) {
                $data[] = $menu;
            }
        }
        if (empty($data)) {
            return [
                'status' => 'Menu not found',
            ];
        } else {
            return $data;
        }
    }

    public function getMenuByPrixMax(mixed $prix): array
    {
        $menus = $this->getAll();
        $data = [];
        foreach ($menus as $menu) {
            if ($menu['prix'] <= $prix) {
                $data[] = $menu;
            }
        }
        if (empty($data)) {
            return [
                'status' => 'Menu not found',
            ];
        } else {
            // sort the array by price
            usort($data, function ($a, $b) {
                return $a['prix'] <=> $b['prix'];
            });
            return $data;
        }

    }
}