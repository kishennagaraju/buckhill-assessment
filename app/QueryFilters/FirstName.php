<?php

    namespace App\QueryFilters;

    use Closure;
    use Illuminate\Pagination\Paginator;


    class FirstName {

        public function handle($request, Closure $next)
        {
            if (!request()->has('first_name')) {
                return $next($request);
            }

            $builder = $next($request);

            return $builder->where('first_name', 'like', '%' . request()->get('first_name') . '%');
        }
    }
