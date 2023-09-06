<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Group
 *
 * @property $id
 * @property $name
 * @property $logo
 * @property $favicon
 * @property $background
 * @property $headline
 * @property $description
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Group extends Model
{
    use HasFactory;

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'headline',
        'description',
        'logo',
        'favicon',
        'background_image',
        'background_color',
        'background_property',
        'title_color',
        'text_color',
        'overlay',
        'active_overlay',
        'footer_logo',
        'footer_border',
        'status'

    ];

    public function setLogoAttribute($logo)
    {
        if ($logo) {
            $name = time() . '_' . $logo->getClientOriginalName();
            $logo->move('upload/groups/logo/', $name);
            $this->attributes['logo'] = $name;
        } else {
            unset($this->attributes['logo']);
        }
    }

    public function getLogoAttribute($logo)
    {
        if ($logo) {
            return asset('upload/groups/logo/' . $logo);
        }
        return null;
    }

    public function setBackgroundImageAttribute($backgroundImage)
    {
        if ($backgroundImage) {
            $name = time() . '_' . $backgroundImage->getClientOriginalName();
            $backgroundImage->move('upload/groups/background_images/', $name);
            $this->attributes['background'] = $name;
        } else {
            unset($this->attributes['background']);
        }
    }

    public function getBackgroundImageAttribute($backgroundImage)
    {
        if ($backgroundImage) {
            return asset('upload/groups/background_images/' . $backgroundImage);
        }
        return null;
    }

    public function setFaviconAttribute($favicon)
    {
        if ($favicon) {
            $name = time() . '_' . $favicon->getClientOriginalName();
            $favicon->move('upload/groups/favicon/', $name);
            $this->attributes['favicon'] = $name;
        } else {
            unset($this->attributes['favicon']);
        }
    }

    public function getFaviconAttribute($favicon)
    {
        if ($favicon) {
            return asset('upload/groups/favicon/' . $favicon);
        }
        return null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    public function user()
    {
        return $this->hasOne(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function homes()
    {
        return $this->belongsTo(Home::class,);
    }
}
