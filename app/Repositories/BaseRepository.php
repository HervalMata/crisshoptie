<?php


namespace App\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use League\Fractal\Manager;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Item as FractalItem;
use League\Fractal\Scope;
use League\Fractal\TransformerAbstract;

class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    protected $manager;

    protected $paginator;

    protected $query;

    /**
     * BaseRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->manager = new BaseManager;
        $this->paginator = new BasePaginator;
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * @inheritDoc
     */
    public function update(array $attributes): bool
    {
        return $this->model->update($attributes);
    }

    /**
     * @inheritDoc
     */
    public function all($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc')
    {
        return $this->model->orderBy($orderBy, $sortBy)->get($columns);
    }

    /**
     * @inheritDoc
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * @inheritDoc
     */
    public function findOneOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function findBy(array $data)
    {
        return $this->model->where($data)->get();
    }

    /**
     * @inheritDoc
     */
    public function findOneBy(array $data)
    {
        return $this->model->where($data)->first();
    }

    /**
     * @inheritDoc
     */
    public function findOneByOrFail(array $data)
    {
        return $this->model->where($data)->firstOrFail();
    }

    /**
     * @inheritDoc
     */
    public function delete(): bool
    {
        return $this->model->delete();
    }

    /**
     * @inheritDoc
     */
    public function processItemTransformer(Model $model, TransformerAbstract $transformer, String $resourceKey, string $includes = null): Scope
    {
        $manager = new ItemAndCollectionManager(new Manager);
        $item = new FractalItem($model, $transformer, $resourceKey);

        $included = explode(',', $includes);
        return $manager->createItemData($item, $included);
    }

    /**
     * @inheritDoc
     */
    public function processCollectionTransformer(Collection $collection, TransformerAbstract $transformer, String $resourceKey, string $includes = null, $perpage = 25): Scope
    {
        $manager = new ItemAndCollectionManager(new Manager);
        $page = app('request')->input('page', 1);
        $fractalCollection = new FractalCollection($collection->forPage($page, $perpage), $transformer, $resourceKey);
        $paginator = $this->paginateArrayResults($collection->all(), $perpage);
        $queryParams = array_diff_key($_GET, array_flip(['page']));
        $paginator->appends($queryParams);
        $fractalCollection->setPaginator(new IlluminatePaginatorAdapter($paginator));

        if (!is_null($includes)) {
            $included = explode(',', $includes);
            return $manager->createCollectionData(
                $fractalCollection, $included
            );
        } else {
            return $manager->createCollectionData(
                $fractalCollection
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function paginateArrayResults(array $data, int $perPage = 50)
    {
        $page = Request::get('page', 1);
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(
            array_values(array_slice($data, $offset, $perPage, true)),
            count($data),
            $perPage,
            $page,
            [
                'path' => app('request')->url(),
                'query' => app('request')->query()
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function processPaginatedTransformer(LengthAwarePaginator $paginator, TransformerAbstract $transformer, String $resourceKey, string $includes = null): Scope
    {
        $items = $paginator->getCollection();
        $resource = new FractalCollection($items, $transformer, $resourceKey);
        $fractalCollection = $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        $manager = new ItemAndCollectionManager(new Manager);

        if (!is_null($includes)) {
            $included = explode(',', $includes);
            return $manager->createCollectionData(
                $fractalCollection, $included
            );
        } else {
            return $manager->createCollectionData(
                $fractalCollection
            );
        }
    }

    public function transformItem(
        Model $model,
        TransformerAbstract $transformer,
        String $resourceKey,
        array $includes = []
    ): array
    {
        $resource = new Item($model, $transformer, $resourceKey);
        return $this->manager->buildData($resource, $includes);
    }

    public function transformCollection(
        FractalCollection $collection,
        TransformerAbstract $transformer,
        String $resourceKey,
        array $includes = []
    ): array
    {
        $resource = new FractalCollection($collection, $transformer, $resourceKey);
        return $this->manager->buildData($resource, $includes);
    }

    public function queryBy($modelOrBuilder, array $params): Builder
    {
        $start = $modelOrBuilder;
        if (!empty($params)) {
            $start->where($params);
        }

        $this->query = $start;

        return $this->query;
    }

    public function getData(
        Builder $builder,
        TransformerAbstract $transformer,
        $isPaginated = true,
        $limit = 50,
        $offset = null,
        $previous = null
    )
    {
        if (!$isPaginated) {
            return $builder->get();
        }

        if ($offset) {
            $collection = $builder->offset($offset)->take($limit)->get();
        } else {
            $collection = $builder->take($limit)->get();
        }

        $newCursor = $collection->last()->id;
        $cursor = new Cursor($offset, $previous, $newCursor, $collection->count());

        $resource = new FractalCollection($collection, $transformer);
        $resource->setCursor($cursor);

        $manager = new Manager;
        return $manager->createData($resource)->toArray();
    }
}
