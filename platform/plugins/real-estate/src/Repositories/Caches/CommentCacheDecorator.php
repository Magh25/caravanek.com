<?php

namespace Botble\RealEstate\Repositories\Caches;

use Botble\RealEstate\Repositories\Interfaces\CommentInterface;
use Botble\RealEstate\Repositories\Interfaces\ReviewInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class CommentCacheDecorator extends CacheAbstractDecorator implements CommentInterface
{
}
