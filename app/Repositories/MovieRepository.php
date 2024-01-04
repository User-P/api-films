<?php

namespace App\Repositories;

use App\Models\Movie;

class MovieRepository implements MovieRepositoryInterface
{
    public function all()
    {
        return Movie::all();
    }

    public function create(array $data)
    {
        return Movie::create($data);
    }

    public function update(array $data, $id){
        Movie::where('id', $id)->update($data);
        return Movie::find($id);
    }

    public function delete($id){
        return Movie::where('id', $id)->delete();
    }

    public function find($id){
        return Movie::find($id);
    }

}
