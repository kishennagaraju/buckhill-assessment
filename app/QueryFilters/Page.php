<?php

    namespace App\QueryFilters;

    use Closure;
    use Illuminate\Pagination\Paginator;


    class Page {

        public function handle($request, Closure $next)
        {
            if (request()->has('page')) {
                $currentPage = request()->get('page');
                Paginator::currentPageResolver(function() use ($currentPage) {
                    return $currentPage;
                });
            }

            return $next($request);
        }
    }
