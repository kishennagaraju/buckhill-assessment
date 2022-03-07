<?php

namespace App\Observers;

use App\Models\Products;
use Illuminate\Support\Str;

class ProductsObserver
{
    /**
     * Handle the Post "created" event.
     *
     * @param  Products  $product
     * @return void
     */
    public function creating(Products $product)
    {
        $product->uuid = Str::uuid();
    }
}
