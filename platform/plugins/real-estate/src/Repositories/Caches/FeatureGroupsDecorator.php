<?php

namespace Botble\RealEstate\Repositories\Caches;

use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\RealEstate\Repositories\Interfaces\FeatureGroupsInterface;

class FeatureGroupsDecorator extends CacheAbstractDecorator implements FeatureGroupsInterface
{
    /**
     * {@inheritDoc}
     */
    public function createSlug($name, $id)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}
