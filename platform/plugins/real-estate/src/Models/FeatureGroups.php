<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeatureGroups extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 're_feature_groups';

    /**
     * @var array
    **/
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'order'
    ];

    /**
     * @var bool disable timestamp
     */
    public $timestamps = false;

    /**
     * @return HasMany
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
