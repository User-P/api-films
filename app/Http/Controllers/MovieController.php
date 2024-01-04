<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\DTOs\MovieDTO;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Repositories\MovieRepositoryInterface;

class MovieController extends ApiController
{
    private $movieRepository;

    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function index()
    {
        return $this->showCollection($this->movieRepository->all(), 'movies');
    }

    public function store(StoreMovieRequest $request)
    {
        $data = $request->validated();
        $movieDTO = new MovieDTO(...$data);

        $movie = $this->movieRepository->create($movieDTO->toArray());
        return $this->showInstance($movie, 'movie');
    }

    public function show(Movie $movie)
    {
        $movie = $this->movieRepository->find($movie->id);
        return $this->showInstance($movie, 'movie');
    }

    public function update(UpdateMovieRequest $request, Movie $movie)
    {
        $data = $request->validated();

        if (empty($data)) {
            return $this->errorResponse('No data provided for update', 400);
        }
        
        $movieDTO = new MovieDTO(...$data);
        $movie = $this->movieRepository->update($movie->id, $movieDTO->toArray());
        return $this->showInstance($movie, 'movie');
    }

    public function destroy(Movie $movie)
    {
        $this->movieRepository->delete($movie->id);
        return $this->successResponse('Movie deleted successfully');
    }
}
