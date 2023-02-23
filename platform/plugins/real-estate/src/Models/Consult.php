<?php

namespace Botble\RealEstate\Models;

use Botble\RealEstate\Enums\ConsultStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consult extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 're_consults';

    /**
     * @var array
     */
    protected $fillable = [
        'property_id',
        'user_id',
        'vendor_id',
        'name',
        'email',
        'phone',
        'property_type',
        'property_name',
        'from_date',
        'to_date',
        'guests',
        'price_pn',
        'total_price',
        'commission',
        'no_nights',
        'status',
        'addons',
        'spaces',
        'unitstype',
        'bookingtype',
        'total_addons',
    ]; 
    
    // 'unitstype',
    // 'monthly_price

    /**
     * @var array
     */
    protected $casts = [
        'status' => ConsultStatusEnum::class,
    ];

    /**
     * @return BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class,'consult_id');
    }
}
