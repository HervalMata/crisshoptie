<?php


namespace App\Repositories;


use League\Fractal\Manager;
use League\Fractal\Serializer\JsonApiSerializer;

class BaseManager
{
    /**
     * @param $resource
     * @param array $includes
     * @return array
     */
    public function BuildData($resource, array $includes = []): array
    {
        $manager = new Manager;
        $manager->setSerializer(new JsonApiSerializer(config('app.url')));
        $manager->parseIncludes($includes);
        return $manager->createData($resource)->toArray();
    }
}
