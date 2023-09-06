<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Class Group
 *
 * @property $id
 * @property $home_id
 * @property $group_id
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class GroupHome extends Model
{
    use HasFactory;

    protected $table = 'group_homes';
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['home_id','group_id'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function home()
    {
        return $this->belongsTo(Home::class);
    }

}
