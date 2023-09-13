<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupTheme extends Pivot
{
    use HasFactory;

    protected $table = 'group_themes';

    protected $fillable =['group_id','theme_id','created_by','status'];
}
