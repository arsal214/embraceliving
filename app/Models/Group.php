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
            $this->attributes['background_image'] = $name;
        } else {
            unset($this->attributes['background_image']);
        }
    }

    public function getBackgroundImageAttribute($backgroundImage)
    {
        if ($backgroundImage) {
            return asset('upload/groups/background_images/' . $backgroundImage);
        }
        return null;
    }


    public function setFooterLogoAttribute($footerLogo)
    {
        if ($footerLogo) {
            $name = time() . '_' . $footerLogo->getClientOriginalName();
            $footerLogo->move('upload/groups/footer_logo/', $name);
            $this->attributes['footer_logo'] = $name;
        } else {
            unset($this->attributes['footer_logo']);
        }
    }

    public function getFooterLogoAttribute($footerLogo)
    {
        if ($footerLogo) {
            return asset('upload/groups/footer_logo/' . $footerLogo);
        }
        return null;
    }

    public function setFooterBorderAttribute($footerBorder)
    {
        if ($footerBorder) {
            $name = time() . '_' . $footerBorder->getClientOriginalName();
            $footerBorder->move('upload/groups/footer_border/', $name);
            $this->attributes['footer_border'] = $name;
        } else {
            unset($this->attributes['footer_border']);
        }
    }

    public function getFooterBorderAttribute($footerBorder)
    {
        if ($footerBorder) {
            return asset('upload/groups/footer_border/' . $footerBorder);
        }
        return null;
    }

    public function setOverlayAttribute($overlay)
    {
        if ($overlay) {
            $name = time() . '_' . $overlay->getClientOriginalName();
            $overlay->move('upload/groups/overlay/', $name);
            $this->attributes['overlay'] = $name;
        } else {
            unset($this->attributes['overlay']);
        }
    }

    public function getOverlayAttribute($overlay)
    {
        if ($overlay) {
            return asset('upload/groups/overlay/' . $overlay);
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function user()
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function homes()
    {
        return $this->belongsToMany(Home::class,'group_homes');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
//    public function themes()
//    {
//        return $this->hasMany(Theme::class);
//    }
    public function themes()
    {
        return $this->belongsToMany(Theme::class, 'group_themes')
            ->using(GroupTheme::class)
            ->withPivot('status');    }
}
