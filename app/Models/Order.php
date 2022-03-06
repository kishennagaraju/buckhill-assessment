<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pipeline\Pipeline;

class Order extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    protected $fillable = [
        'uuid',
        'user_id',
        'order_status_id',
        'payment_id',
        'products',
        'address',
        'delivery_fee',
        'amount',
        'shipped_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'shipped_at' => 'datetime',
        'products' => 'json',
        'address' => 'array'
    ];

    protected $hidden = [
        'id',
        'user_id',
        'order_status_id',
        'payment_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function order_status()
    {
        return $this->belongsTo(OrderStatuses::class, 'order_status_id', 'id');
    }

    public function payment()
    {
        return $this->hasOne(Payments::class, 'id', 'payment_id');
    }

    public function order_products()
    {
        return $this->belongsToJson(Products::class, 'products[]->product', 'uuid');
    }

    public function getAllOrders($relationships = [], $userId = null)
    {
        $query = $this->newQuery()->with($relationships);

        if (!empty($userId)) {
            $query->where('user_id', '=', $userId);
        }

        return app(Pipeline::class)
            ->send($query)
            ->through([
                \App\QueryFilters\Page::class,
                \App\QueryFilters\Sort::class,
            ])
            ->thenReturn()
            ->paginate(\request()->has('limit') ? \request()->get('limit') : env('PAGINATION_LIMIT', 10));
    }

    public function getOrderByUuid($uuid, $relationships = [])
    {
        return $this->newQuery()->with($relationships)->where('uuid', '=', $uuid)->firstOrFail();
    }

    public function createOrder($data)
    {
        return $this->newQuery()->create();
    }

    public function updateOrderByUuid($uuid, $data)
    {
        $orderDetails = $this->newQuery()->where('uuid', '=', $uuid)->firstOrFail();
        return $this->newQuery()->find($orderDetails->id)->updateOrFail($data);
    }

    public function deleteOrderByUuid($uuid)
    {
        if ($this->newQuery()->where('uuid', '=', $uuid)->exists()) {
            return $this->newQuery()->where('uuid', '=', $uuid)->delete();
        }

        throw new ModelNotFoundException();
    }
}
