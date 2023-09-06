<?php

namespace App\Models;

use App\Home;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Region
 *
 * @property $id
 * @property $name
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property Home[] $homes
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Region extends Model
{

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name','status','group_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function homes()
    {
        return $this->hasMany('App\Home', 'region_id', 'id');
    }


}
