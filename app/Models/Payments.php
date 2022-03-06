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

    /**
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
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

    /**
     * @param  string  $uuid
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function getPaymentByUuid(string $uuid)
    {
        return $this->newQuery()->where('uuid', '=', $uuid)->firstOrFail();
    }

    /**
     * @param  array  $data
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function createPayment(array $data)
    {
        return $this->newQuery()->create($data);
    }

    /**
     * @param  string  $uuid
     * @param  array   $data
     *
     * @return bool
     * @throws \Throwable
     */
    public function updatePaymentByUuid(string $uuid, array $data)
    {
        $paymentDetails = $this->newQuery()->where('uuid', '=', $uuid)->firstOrFail();
        return $this->newQuery()->find($paymentDetails->id)->updateOrFail($data);
    }

    /**
     * @param  string  $uuid
     *
     * @return mixed
     */
    public function deletePaymentByUuid(string $uuid)
    {
        if ($this->newQuery()->where('uuid', '=', $uuid)->exists()) {
            return $this->newQuery()->where('uuid', '=', $uuid)->delete();
        }

        throw new ModelNotFoundException();
    }
}
