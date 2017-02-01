<?php namespace Barcamp\Talks\Models;

use Model;
use October\Rain\Database\Traits\SoftDelete as SoftDeleteTrait;
use October\Rain\Database\Traits\Sortable as SortableTrait;
use October\Rain\Database\Traits\Validation as ValidationTrait;

/**
 * Category Model.
 */
class Type extends Model
{
    use SoftDeleteTrait;

    use SortableTrait;

    use ValidationTrait;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'barcamp_talks_types';

    /**
     * @var array Model rules.
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:barcamp_talks_categories',
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
    public $belongsToMany = [
        'talks' => [
            'Barcamp\Talks\Models\Talk',
            'order' => 'name desc',
            'scope' => 'isApproved',
            'timestamps' => true,
        ],
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
