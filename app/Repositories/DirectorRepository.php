<?php

namespace App\Repositories;

use App\Models\Director;

class DirectorRepository implements DirectorRepositoryInterface
{
    public function all()
    {
        return Director::all();
    }

    public function create(array $data)
    {
        return Director::create($data);
    }

    public function update(array $data, $id)
    {
        Director::where('id', $id)->update($data);
        return Director::find($id);
    }

    public function delete($id)
    {
        return Director::where('id', $id)->delete();
    }

    public function find($id)
    {
        return Director::find($id);
    }
}
