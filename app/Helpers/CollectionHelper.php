<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;

class CollectionHelper
{
    public static function paginate($items, $perPage, $page = null, $pageName = "page", $orderByColumn = null, $orderByType = null, $arrayInsert = [])
    {
        if (!is_null($orderByColumn)) {
            $direction = ($orderByType === 'desc') ? -1 : 1;

            usort($items, function ($a, $b) use ($orderByColumn, $direction) {
                $valueA = is_numeric($a[$orderByColumn]) ? (int) $a[$orderByColumn] : $a[$orderByColumn];
                $valueB = is_numeric($b[$orderByColumn]) ? (int) $b[$orderByColumn] : $b[$orderByColumn];

                if ($valueA === $valueB) {
                    return 0;
                }

                return ($valueA < $valueB) ? -$direction : $direction;
            });
        }

        $page = $page ?: Paginator::resolveCurrentPage() ?: 1;
        $items = $items instanceof Collection ? $items : Collection::make($items);
        $data = $items->forPage($page, $perPage)->toArray();

        if (!empty($arrayInsert)) {
            $paginate = new LengthAwarePaginator(array_values($data), $items->count(), $perPage, $page, [
                'path' => URL::current(),
                'pageName' => $pageName,
            ]);
            $paginateArray = $paginate->toArray();
            $paginateArray['count'] = $arrayInsert;

            return $paginateArray;
        }

        return new LengthAwarePaginator(array_values($items->forPage($page, $perPage)->toArray()), $items->count(), $perPage, $page, [
            'path' => url()->current(),
            'pageName' => $pageName,
        ]);
    }
}
