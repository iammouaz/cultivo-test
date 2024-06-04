<?php

namespace App\Services\Repositories;

class CategoryRepository extends BaseRepository
{
    protected $model = \App\Models\Category::class;
    public function getAll(){
        return $this->query()->active()->get();
    }
}
