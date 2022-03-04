<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pipeline\Pipeline;

class Categories extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $hidden = [
        'id'
    ];

    protected $fillable = [
        'uuid',
        'title',
        'slug',
    ];

    public function products()
    {
        return $this->hasMany(Products::class, 'category_uuid', 'uuid');
    }

    public function getAllCategories()
    {
        return app(Pipeline::class)
            ->send($this->newQuery())
            ->through([
                \App\QueryFilters\Page::class,
                \App\QueryFilters\Sort::class,
            ])
            ->thenReturn()
            ->paginate(\request()->has('limit') ? \request()->get('limit') : env('PAGINATION_LIMIT', 10));
    }

    public function getCategoryByUuid($uuid)
    {
        return $this->newQuery()->where('uuid', '=', $uuid)->firstOrFail();
    }

    public function createCategory($data)
    {
        return $this->newQuery()->create($data);
    }

    public function updateCategoryByUuid($uuid, $data)
    {
        if ($categoryDetails = $this->newQuery()->where('uuid', '=', $uuid)->firstOrFail()) {
            return $this->newQuery()->findOrFail($categoryDetails->id)->update($data);
        }
    }

    public function deleteCategoryByUuid($uuid)
    {
        if ($this->newQuery()->where('uuid', '=', $uuid)->exists()) {
            return $this->newQuery()->where('uuid', '=', $uuid)->delete();
        }

        throw new ModelNotFoundException();
    }
}
