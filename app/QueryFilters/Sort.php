<?php

    namespace App\QueryFilters;

    use Closure;


    class Sort {

        public function handle($request, Closure $next)
        {
            $builder = $next($request);
            $sort = request()->has('sort') ? request()->get('sort') : env('DEFAULT_SORT', 'id');
            $dir = request()->get('desc') ? 'desc' : env('DEFAULT_SORT_DIRECTION', 'asc');

            return $builder->orderBy($sort, $dir);
        }
    }
