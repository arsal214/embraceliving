<?php

namespace App\Models;

use App\Models\GroupHome;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Home
 *
 * @property $id
 * @property $title
 * @property $region_id
 * @property $code
 * @property $identifier
 * @property $heygo_token
 * @property $template_link
 * @property $created_at
 * @property $updated_at
 *
 * @property GroupHome[] $groupHomes
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Home extends Model
{

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['region_id', 'group_id',
        'title', 'code', 'identifier', 'heygo_token',
        'template_link', 'status'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function group()
    {
        return $this->hasOne(Group::class);
    }
}
