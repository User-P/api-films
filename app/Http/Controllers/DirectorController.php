<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDirectorRequest;
use App\Http\Requests\UpdateDirectorRequest;
use App\Models\Director;

class DirectorController extends ApiController
{
    public function index()
    {
        return $this->showCollection(Director::all(), 'directors');
    }

    public function store(StoreDirectorRequest $request)
    {
        return $this->showInstance(Director::create($request->validated()), 'director');
    }

    public function show(Director $director)
    {
        return $this->showInstance($director, 'director');
    }

    public function update(UpdateDirectorRequest $request, Director $director)
    {
        $director->update($request->validated());
        return $this->showInstance($director, 'director');
    }

    public function destroy(Director $director)
    {
        $director->delete();
        return $this->successResponse('Director deleted successfully');
    }
}
