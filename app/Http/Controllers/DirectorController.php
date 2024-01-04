<?php

namespace App\Http\Controllers;

use App\Models\Director;
use App\DTOs\DirectorDTO;
use App\Http\Requests\StoreDirectorRequest;
use App\Http\Requests\UpdateDirectorRequest;
use App\Repositories\DirectorRepositoryInterface;

class DirectorController extends ApiController
{
    private $directorRepository;

    public function __construct(DirectorRepositoryInterface $directorRepository)
    {
        $this->directorRepository = $directorRepository;
    }

    public function index()
    {
        return $this->showCollection($this->directorRepository->all(), 'directors');
    }

    public function store(StoreDirectorRequest $request)
    {
        $data = $request->validated();
        $movieDTO = new DirectorDTO(...$data);

        $director = $this->directorRepository->create($movieDTO->toArray());
        return $this->showInstance($director, 'director');
    }

    public function show(Director $director)
    {
        $director = $this->directorRepository->find($director->id);
        return $this->showInstance($director, 'director');
    }

    public function update(UpdateDirectorRequest $request, Director $director)
    {
        $data = $request->validated();

        if (empty($data)) {
            return $this->errorResponse('No data provided for update', 400);
        }

        $movieDTO = new DirectorDTO(...$data);
        $director = $this->directorRepository->update($movieDTO->toArray(), $director->id);
        return $this->showInstance($director, 'director');
    }

    public function destroy(Director $director)
    {
        $this->directorRepository->delete($director->id);
        return $this->successResponse('Director deleted successfully');
    }
}
