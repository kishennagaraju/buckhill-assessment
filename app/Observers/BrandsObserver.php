<?php

namespace App\Observers;

use App\Models\Brands;
use Illuminate\Support\Str;

class BrandsObserver
{
    /**
     * Handle the Post "created" event.
     *
     * @param  Brands  $brand
     * @return void
     */
    public function creating(Brands $brand)
    {
        $brand->slug = Str::slug($brand->title);
        $brand->uuid = Str::uuid();
    }

    /**
     * Handle the Put "updated" event.
     *
     * @param  Brands  $brand
     * @return void
     */
    public function updating(Brands $brand)
    {
        $brand->slug = Str::slug($brand->title);
    }
}
