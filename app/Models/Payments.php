<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pipeline\Pipeline;

class Payments extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'details' => 'array'
    ];

    protected $fillable = [
        'title',
        'details'
    ];

    protected $hidden = [
        'id'
    ];

    public function getAllPayments()
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

    public function getPaymentByUuid($uuid)
    {
        return $this->newQuery()->where('uuid', '=', $uuid)->firstOrFail();
    }

    public function createPayment($data)
    {
        return $this->newQuery()->create($data);
    }

    public function updatePaymentByUuid($uuid, $data)
    {
        $paymentDetails = $this->newQuery()->where('uuid', '=', $uuid)->firstOrFail();
        return $this->newQuery()->find($paymentDetails->id)->updateOrFail($data);
    }

    public function deletePaymentByUuid($uuid)
    {
        if ($this->newQuery()->where('uuid', '=', $uuid)->exists()) {
            return $this->newQuery()->where('uuid', '=', $uuid)->delete();
        }

        throw new ModelNotFoundException();
    }
}
