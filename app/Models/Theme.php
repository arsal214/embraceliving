<?php

namespace App\Models;

use App\Models\Group;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Theme
 *
 * @property $id
 * @property $group_id
 * @property $name
 * @property $logo
 * @property $background_image
 * @property $background_property
 * @property $background_color
 * @property $overlay
 * @property $active_overlay
 * @property $footer_logo
 * @property $footer_border
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property Group $group
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Theme extends Model
{
    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['group_id','name','logo','background_image','background_property','background_color','overlay','active_overlay','footer_logo','footer_border','status'];
    public function setLogoAttribute($logo)
    {
        if ($logo) {
            $name = time() . '_' . $logo->getClientOriginalName();
            $logo->move('upload/themes/logo/', $name);
            $this->attributes['logo'] = $name;
        } else {
            unset($this->attributes['logo']);
        }
    }

    public function getLogoAttribute($logo)
    {
        if ($logo) {
            return asset('upload/themes/logo/' . $logo);
        }
        return null;
    }

    public function setBackgroundImageAttribute($backgroundImage)
    {
        if ($backgroundImage) {
            $name = time() . '_' . $backgroundImage->getClientOriginalName();
            $backgroundImage->move('upload/themes/background_images/', $name);
            $this->attributes['background_image'] = $name;
        } else {
            unset($this->attributes['background_image']);
        }
    }

    public function getBackgroundImageAttribute($backgroundImage)
    {
        if ($backgroundImage) {
            return asset('upload/themes/background_images/' . $backgroundImage);
        }
        return null;
    }


    public function setFooterLogoAttribute($footerLogo)
    {
        if ($footerLogo) {
            $name = time() . '_' . $footerLogo->getClientOriginalName();
            $footerLogo->move('upload/themes/footer_logo/', $name);
            $this->attributes['footer_logo'] = $name;
        } else {
            unset($this->attributes['footer_logo']);
        }
    }

    public function getFooterLogoAttribute($footerLogo)
    {
        if ($footerLogo) {
            return asset('upload/themes/footer_logo/' . $footerLogo);
        }
        return null;
    }

    public function setFooterBorderAttribute($footerBorder)
    {
        if ($footerBorder) {
            $name = time() . '_' . $footerBorder->getClientOriginalName();
            $footerBorder->move('upload/themes/footer_border/', $name);
            $this->attributes['footer_border'] = $name;
        } else {
            unset($this->attributes['footer_border']);
        }
    }

    public function getFooterBorderAttribute($footerBorder)
    {
        if ($footerBorder) {
            return asset('upload/themes/footer_border/' . $footerBorder);
        }
        return null;
    }

    public function setOverlayAttribute($overlay)
    {
        if ($overlay) {
            $name = time() . '_' . $overlay->getClientOriginalName();
            $overlay->move('upload/themes/overlay/', $name);
            $this->attributes['overlay'] = $name;
        } else {
            unset($this->attributes['overlay']);
        }
    }

    public function getOverlayAttribute($overlay)
    {
        if ($overlay) {
            return asset('upload/themes/overlay/' . $overlay);
        }
        return null;
    }
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
//     */
//    public function group()
//    {
//        return $this->belongsTo(Group::class);
//    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_themes')
            ->using(GroupTheme::class)
            ->withPivot('status');
    }


}
