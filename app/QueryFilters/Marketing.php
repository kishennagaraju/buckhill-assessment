<?php

    namespace App\QueryFilters;

    use Closure;
    use Illuminate\Pagination\Paginator;


    class Marketing {

        public function handle($request, Closure $next)
        {
            if (!request()->has('is_marketing')) {
                return $next($request);
            }

            $builder = $next($request);

            return $builder->where('is_marketing', '=', request()->get('is_marketing'));
        }
    }
