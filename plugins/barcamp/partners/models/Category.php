<?php

namespace Barcamp\Partners\Models;

use Model;
use October\Rain\Database\Traits\SoftDelete as SoftDeleteTrait;
use October\Rain\Database\Traits\Sortable as SortableTrait;
use October\Rain\Database\Traits\Validation as ValidationTrait;

class Category extends Model
{
    use SoftDeleteTrait;

    use SortableTrait;

    use ValidationTrait;

    protected $guarded = [];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'barcamp_partners_categories';

    /**
     * @var array Model rules.
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:barcamp_partners_categories',
    ];

    /**
     * @var array Datetime fields.
     */
    public $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Belongs to many relationships.
     *
     * @var array
     */
    public $hasMany = [
        'partner' => ['Barcamp\Partners\Models\Partner',
            'table' => 'barcamp_partners_partners',
            'order' => 'sort_order asc'
        ]
    ];

    /**
     * Fetch only enabled categories.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeIsEnabled($query)
    {
        return $query->where('enabled', true);
    }
}
