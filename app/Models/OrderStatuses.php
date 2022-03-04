<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pipeline\Pipeline;

class OrderStatuses extends Model
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
    ];

    public function getAllOrderStatuses()
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

    public function getOrderStatusByUuid($uuid)
    {
        return $this->newQuery()->where('uuid', '=', $uuid)->firstOrFail();
    }

    public function createOrderStatus($data)
    {
        return $this->newQuery()->create($data);
    }

    public function updateOrderStatusByUuid($uuid, $data)
    {
        $orderStatusDetails = $this->newQuery()->where('uuid', '=', $uuid)->firstOrFail();
        return $this->newQuery()->find($orderStatusDetails->id)->updateOrFail($data);
    }

    public function deleteOrderStatusByUuid($uuid)
    {
        if ($this->newQuery()->where('uuid', '=', $uuid)->exists()) {
            return $this->newQuery()->where('uuid', '=', $uuid)->delete();
        }

        throw new ModelNotFoundException();
    }
}
