<?php

    namespace App\QueryFilters;

    use Closure;
    use Illuminate\Pagination\Paginator;


    class CreatedAt {

        public function handle($request, Closure $next)
        {
            if (!request()->has('created_at')) {
                return $next($request);
            }

            $builder = $next($request);

            return $builder->where('created_at', '=', date('Y-m-d', strtotime(request()->get('created_at'))));
        }
    }
