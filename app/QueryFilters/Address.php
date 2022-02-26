<?php

    namespace App\QueryFilters;

    use Closure;
    use Illuminate\Pagination\Paginator;


    class Address {

        public function handle($request, Closure $next)
        {
            if (!request()->has('address')) {
                return $next($request);
            }

            $builder = $next($request);

            return $builder->where('address', 'like', '%' . request()->get('address') . '%');
        }
    }
