<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FeatureCategories extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 're_feature_types';

    /**
     * @var bool disable timestamp
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [ 
        'feature_id',
        'type_id',
    ];


    /**
    * @return BelongsToMany
    */
    public function categories(): BelongsTo
    {
        return $this->belongsTo(Feature::class, 're_feature_types', 'feature_id', 'type_id');
    }
  
}


