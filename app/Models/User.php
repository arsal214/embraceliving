<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 *
 * @property $id
 * @property $first_name
 * @property $last_name
 * @property $email
 * @property $username
 * @property $email_verified_at
 * @property $password
 * @property $image
 * @property $type
 * @property $remember_token
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['group_id', 'first_name', 'last_name', 'email', 'image', 'type', 'username', 'password', 'status'];

    public function setImageAttribute($image)
    {
        if ($image) {
            $name = time() . '_' . $image->getClientOriginalName();
            $image->move('upload/users/image/', $name);
            $this->attributes['image'] = $name;
        } else {
            unset($this->attributes['image']);
        }
    }

    public function getImageAttribute($image)
    {
        if ($image) {
            return asset('upload/users/image/' . $image);
        }
        return null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
