<?php

    namespace App\QueryFilters;

    use Closure;
    use Illuminate\Pagination\Paginator;


    class Email {

        public function handle($request, Closure $next)
        {
            if (!request()->has('email')) {
                return $next($request);
            }

            $builder = $next($request);

            return $builder->where('email', 'like', '%' . request()->get('email') . '%');
        }
    }
