<?php

namespace App\Services\Repositories;

class OriginRepository extends BaseRepository
{
    protected $model = \App\Models\Origin::class;
    public function getAll(){
        return $this->query()->active()->get();
    }
}
