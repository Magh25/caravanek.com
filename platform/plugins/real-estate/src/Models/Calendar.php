<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Calendar extends BaseModel
{
    use EnumCastable;

    /**
     * @var string
     */
    protected $table = 're_calendar';

    /**
     * @var array
     */
    protected $fillable = [
        're_properties_id',
        'date',
        'price',
        'status', 
    ];

    /**
     * @var array
     */
    
    /**
     * @return BelongsTo
     */
    public function properties(): BelongsTo
    {
        return $this->belongsTo(Type::class, 're_properties_id');
    } 
  
}
