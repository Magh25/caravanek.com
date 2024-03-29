<?php

namespace Botble\RealEstate\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface AddonInterface extends RepositoryInterface
{
    /**
     * @param string $name
     * @param int $id
     * @return mixed
    **/
    public function createSlug($name, $id);
} 