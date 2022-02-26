<?php

    namespace App\QueryFilters;

    use Closure;
    use Illuminate\Pagination\Paginator;


    class Phone {

        public function handle($request, Closure $next)
        {
            if (!request()->has('phone')) {
                return $next($request);
            }

            $builder = $next($request);

            return $builder->where('phone', 'like', '%' . request()->get('phone') . '%');
        }
    }
