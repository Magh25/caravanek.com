<?php

namespace Botble\RealEstate\Repositories\Interfaces;

use Botble\RealEstate\Models\Property;
use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface BlogInterface extends RepositoryInterface
{
    /**
     * @param int $propertyId
     * @param int $limit
     * @return array
     */
    public function getRelatedBlogs(int $propertyId, $limit = 4, array $with = []);

    /**
     * @param array $filters
     * @param array $params
     * @return array
     */
    public function getBlogs($filters = [], $params = []);

    /**
     * @param int $propertyId
     * @param array $with
     * @return Property
     */
    public function getBlog(int $propertyId, array $with = []);

    /**
     * @param array $condition
     * @param int $limit
     * @param array $with
     * @return array
     */
    public function getBlogsByConditions(array $condition, $limit, array $with = [], array $withCount = []);
}
