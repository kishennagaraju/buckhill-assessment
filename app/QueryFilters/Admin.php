<?php

    namespace App\QueryFilters;

    use Closure;
    use Illuminate\Pagination\Paginator;


    class Admin {

        public function handle($request, Closure $next)
        {
            $builder = $next($request);

            return $builder->where('is_admin', '=', 1);
        }
    }
