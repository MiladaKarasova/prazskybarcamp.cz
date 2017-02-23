<?php

namespace Barcamp\Partners\Models;

use Model;
use October\Rain\Database\Traits\SoftDelete as SoftDeletingTrait;
use October\Rain\Database\Traits\Sortable as SortableTrait;
use October\Rain\Database\Traits\Validation as ValidationTrait;

class Partner extends Model
{
    use SoftDeletingTrait;

    use SortableTrait;

    use ValidationTrait;

    protected $guarded = [];

    public $table = 'barcamp_partners_partners';

    public $rules = [
        'name' => 'required|max:255',
        'slug' => 'required|unique:barcamp_partners_partners',
        'enabled' => 'boolean'
    ];

    public $dates = ['created_at', 'updated_at', 'deleted_at'];

    public $attachOne = [
        'logo' => ['System\Models\File']
    ];

    public $belongsTo = [
        'category' => 'Barcamp\Partners\Models\Category'
    ];

    public function scopeIsEnabled($query)
    {
        return $query->where('enabled', true);
    }
}
