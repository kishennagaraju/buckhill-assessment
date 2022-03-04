<?php

namespace App\Observers;

use App\Models\Categories;
use Illuminate\Support\Str;

class CategoriesObserver
{
    /**
     * Handle the Post "created" event.
     *
     * @param  Categories  $category
     * @return void
     */
    public function creating(Categories $category)
    {
        $category->slug = Str::slug($category->title);
        $category->uuid = Str::uuid();
    }

    /**
     * Handle the Post "created" event.
     *
     * @param  Categories  $category
     * @return void
     */
    public function updating(Categories $category)
    {
        $category->slug = Str::slug($category->title);
    }
}
