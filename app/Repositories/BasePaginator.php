<?php


namespace App\Repositories;


use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class BasePaginator
{

    /**
     * @param LengthAwarePaginator $paginator
     * @param TransformerAbstract $transformer
     * @param $resourceKey
     * @return Collection
     */
    public function paginator(LengthAwarePaginator $paginator, TransformerAbstract $transformer, $resourceKey)
    {
        $collection = $paginator->getCollection();
        $resource = new Collection($collection, $transformer, $resourceKey);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return $resource;
    }
}
