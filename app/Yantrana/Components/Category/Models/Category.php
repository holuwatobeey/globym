<?php
/*
* Category.php - Model file
*
* This file is part of the Category component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Category\Models;

use App\Yantrana\Core\BaseModel;
use App\Yantrana\Components\SpecificationsPreset\Models\Specification;
use Cache;

class Category extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'categories';

    /**
     * Caching Ids related to this model which may need to clear on add/update/delete.
     *
     * @var string
     *----------------------------------------------------------------------- */
    protected $cacheIds = [
        'cache.categories.with.child.all',
        'cache.categories.all',
        'cache.categories.all.active',
        'cache.categories.where.active.first',
    ];

    /**
     * @var array - The attributes that should be casted to native types..
     */
    protected $casts = [
        'id' => 'integer',
        'status' => 'integer',
        'parent_id' => 'integer',
        'name' => 'string',
    ];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * Scope a query to only include status.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query->whereStatus($status);
    }

    /**
     * Scope a query to only include search id.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereCatId($query, $ID)
    {
        return $query->whereId($ID);
    }

    /**
     * Scope a query to only include selected field.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSelectField($query)
    {
        return $query->select('id', 'name', 'status', 'parent_id');
    }

    /**
     * Scope a query to only include name.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeName($query, $name)
    {
        return $query->whereName($name);
    }

    /**
     * Scope a query to only include name.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeParent($query, $parentId)
    {
        return $query->whereParent_id($parentId);
    }

	/**
	* Get the parent category.
	*/
    public function parentCategory()
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

    /**
    * Get the parent category.
    */
    public function parentCategories()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
    * Get the parent category.
    */
    public function childCategories()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('name', 'asc');
    }

    /**
    * Get the parent category.
    */
    public function childCategoriesRecursive()
    {
        return $this->childCategories()->with('childCategoriesRecursive');
    }

    /**
    * Get the parent category.
    */
    public function childrenCategories()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('name', 'asc')->whereStatus(1);
    }

    /**
    * Get the parent category.
    */
    public function children()
    {
        return $this->childrenCategories()->with('children');
    }
}
