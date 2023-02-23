<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feature extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 're_features';

    /**
     * @var bool disable timestamp
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'icon',
        'type',
        'group',
        'select_options',
        'show_on_listing',
        'order',
    ];


    /**
    * @return BelongsToMany
    */
    public function types(): BelongsToMany
    {
        return $this->belongsToMany(Type::class, 're_feature_types', 'feature_id', 'type_id');
    }
 
    /**
     * @return BelongsToMany
     */
    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 're_property_features', 'feature_id', 'property_id');
    }
}


