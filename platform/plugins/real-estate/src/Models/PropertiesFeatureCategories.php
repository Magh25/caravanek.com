<?php 
namespace Botble\RealEstate\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PropertiesFeatureCategories extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'feature_categories';

    /**
     * @var bool disable timestamp
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [ 
        'feature_id',
        'property_id',
        'value',
    ];


    /**
    * @return BelongsToMany
    */
    public function categories(): BelongsTo
    {
        return $this->belongsTo(Feature::class, 'feature_categories', 'feature_id', 'property_id');
    }
  
}


