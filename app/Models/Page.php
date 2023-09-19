<?php

namespace App\Models;

use App\Models\Group;
use App\Models\Theme;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Page
 *
 * @property $id
 * @property $group_id
 * @property $theme_id
 * @property $title
 * @property $slug
 * @property $top_line
 * @property $bottom_line
 * @property $page_type
 * @property $reference
 * @property $redirect_type
 * @property $box_color
 * @property $line_color
 * @property $border_type
 * @property $attachment_id
 * @property $is_default
 * @property $is_monitor
 * @property $script
 * @property $page_icon
 * @property $created_at
 * @property $updated_at
 *
 * @property Group $group
 * @property Theme $theme
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Page extends Model
{

    static $rules = [
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['group_id','theme_id','title','slug','top_line','bottom_line','page_type',
        'reference','redirect_type','title_color','text_color','border_type',
        'attachment_id','is_default','is_monitor','script','page_icon','status'];


    public function setPageIconAttribute($page_icon)
    {
        if ($page_icon) {
            $name = time() . '_' . $page_icon->getClientOriginalName();
            $page_icon->move('upload/pages/icon/', $name);
            $this->attributes['page_icon'] = $name;
        } else {
            unset($this->attributes['page_icon']);
        }
    }

    public function getPageIconAttribute($page_icon)
    {
        if ($page_icon) {
            return asset('upload/pages/icon/' . $page_icon);
        }
        return null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function group()
    {
        return $this->hasOne(Group::class, 'id', 'group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function theme()
    {
        return $this->hasOne('App\Models\Theme', 'id', 'theme_id');
    }


}
