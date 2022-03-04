<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pipeline\Pipeline;

class Products extends Model
{
    use HasFactory;
    use SoftDeletes;
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'metadata' => 'array',
    ];

    protected $fillable = [
        'uuid',
        'category_uuid',
        'title',
        'price',
        'description',
        'metadata'
    ];

    protected $hidden = [
        'id'
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_uuid', 'uuid');
    }

    public function brand()
    {
        return $this->belongsToJson(Brands::class, 'metadata->brand', 'uuid');
    }

    public function getAllProducts($relationships = [])
    {
        return app(Pipeline::class)
            ->send($this->newQuery()->with($relationships))
            ->through([
                \App\QueryFilters\Page::class,
                \App\QueryFilters\Sort::class,
            ])
            ->thenReturn()
            ->paginate(\request()->has('limit') ? \request()->get('limit') : env('PAGINATION_LIMIT', 10));
    }

    public function getProductByUuid($uuid, $relationships = [])
    {
        return $this->newQuery()->with($relationships)->where('uuid', '=', $uuid)->firstOrFail();
    }

    public function createProduct($data)
    {
        return $this->newQuery()->create($data);
    }

    public function updateProductByUuid($uuid, $data)
    {
        $brandDetails = $this->newQuery()->where('uuid', '=', $uuid)->firstOrFail();
        return $this->newQuery()->find($brandDetails->id)->updateOrFail($data);
    }

    public function deleteProductByUuid($uuid)
    {
        if ($this->newQuery()->where('uuid', '=', $uuid)->exists()) {
            return $this->newQuery()->where('uuid', '=', $uuid)->delete();
        }

        throw new ModelNotFoundException();
    }
}
