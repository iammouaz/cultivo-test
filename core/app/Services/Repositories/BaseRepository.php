<?php

namespace App\Services\Repositories;

abstract class BaseRepository
{
   protected $model;
    protected $paginationSize;
   public function __construct($app)
   {
      $this->paginationSize = getPaginate();
   }
    public function getAll(){
        return $this->model::all();
    }
    public function query(){
        return $this->model::query();
    }
    public function find($id){
        return $this->model::find($id);
    }


}
