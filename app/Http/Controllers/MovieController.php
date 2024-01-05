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

    /**
     * @OA\Get(
     *     path="/movies",
     *     summary="List all movies",
     *     tags={"Movies"},
     *     @OA\Response(
     *         response=200,
     *         description="Returns a list of movies",
     *     )
     * )
     */

    public function index()
    {
        return $this->showCollection($this->movieRepository->all(), 'movies');
    }
    /**
     * @OA\Post(
     *     path="/movies",
     *     summary="Create a new movie",
     *     tags={"Movies"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data for the new movie",
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Movie created",
     *     )
     * )
     */

    public function store(StoreMovieRequest $request)
    {
        $data = $request->validated();
        $movieDTO = new MovieDTO(...$data);

        $movie = $this->movieRepository->create($movieDTO->toArray());
        return $this->showInstance($movie, 'movie');
    }

    /**
     * @OA\Get(
     *     path="/movies/{id}",
     *     summary="Get details of a specific movie",
     *     tags={"Movies"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Unique identifier of the movie",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success, returns the movie details",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Movie not found"
     *     )
     * )
     */


    public function show(Movie $movie)
    {
        $movie = $this->movieRepository->find($movie->id);
        return $this->showInstance($movie, 'movie');
    }

    /**
     * @OA\Put(
     *     path="/movies/{id}",
     *     summary="Update an existing movie",
     *     tags={"Movies"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Unique identifier of the movie to be updated",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Partial or full data to update a movie. None of the fields are required.",
     *         required=false,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success, returns updated movie details",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="No data provided for update"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Movie not found"
     *     )
     * )
     */

    public function update(UpdateMovieRequest $request, Movie $movie)
    {
        $data = $request->validated();

        if (empty($data)) {
            return $this->errorResponse('No data provided for update', 400);
        }

        $movieDTO = new MovieDTO(...$data);

        $movie = $this->movieRepository->update($movieDTO->toArray(), $movie->id);
        return $this->successResponse($movie, 'movie');
    }

    /**
     * @OA\Delete(
     *     path="/movies/{id}",
     *     summary="Deletes a movie",
     *     tags={"Movies"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Unique identifier of the movie to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Movie deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Movie not found"
     *     )
     * )
     */
    public function destroy(Movie $movie)
    {
        $this->movieRepository->delete($movie->id);
        return $this->successResponse('Movie deleted successfully');
    }
}
