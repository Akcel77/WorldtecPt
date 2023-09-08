<?php

namespace App\Service;

use App\Entity\Menu;
use App\Repository\MenuRepository;

class MenuService
{

    /**
     * @param MenuRepository $menuRepository
     */
    public function __construct(private MenuRepository $menuRepository)
    {

    }


    /**
     * @return Menu[]
     */
    public function findAll()
    {
        return $this->menuRepository->findAllForTwig();

    }
}