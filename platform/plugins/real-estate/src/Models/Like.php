<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Eloquent;

class Like extends Eloquent
{
    use EnumCastable;

    /**
     * @var string
     */
    protected $table = 're_likes';

    /**
     * @var array
     */
    protected $fillable = [
        'account_id',
        'able_id',
        'able_type', 
    ];

    /**
     * @var array
     */
    

    // public function create($value){
    //     $this->create($value);
    // } 
    /*/
     * Get the parent reviewable model (property).
     */
    public function able()
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class)->withDefault();
    }

    /**
     * @return HasMany
     */
    // public function meta()
    // {
    //     return $this->hasMany(ReviewMeta::class, 'review_id');
    // }
}
