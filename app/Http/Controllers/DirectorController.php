<?php

namespace App\Http\Controllers;

use App\Models\Director;
use App\DTOs\DirectorDTO;
use App\Http\Requests\StoreDirectorRequest;
use App\Http\Requests\UpdateDirectorRequest;
use App\Repositories\DirectorRepositoryInterface;

/**
 * @OA\Info(title="API-FILMS", version="1.0")
 * @OA\Server(url="https://api-films.test")
 */

class DirectorController extends ApiController
{
    private $directorRepository;

    public function __construct(DirectorRepositoryInterface $directorRepository)
    {
        $this->directorRepository = $directorRepository;
    }

    /**
     * @OA\Get(
     *     path="/directors",
     *     summary="List all directors",
     *     tags={"Directors"},
     *     @OA\Response(
     *         response=200,
     *         description="Returns a list of directors",
     *     )
     * )
     */
    public function index()
    {
        return $this->showCollection($this->directorRepository->all(), 'directors');
    }

    /**
     * @OA\Post(
     *     path="/directors",
     *     summary="Create a new director",
     *     tags={"Directors"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data for the new director",
     *         @OA\JsonContent(
     *             required={"name", "image", "description", "nationality", "birth"},
     *             @OA\Property(property="name", type="string", description="The name of the director", example="Quentin Tarantino"),
     *             @OA\Property(property="image", type="string", description="URL or path to the director's image", example="http://example.com/image.jpg"),
     *             @OA\Property(property="description", type="string", description="Brief description about the director", example="American film director and actor known for..."),
     *             @OA\Property(property="nationality", type="string", description="The nationality of the director", example="American"),
     *             @OA\Property(property="birth", type="string", format="date", description="Director's date of birth", example="1963-03-27"),
     *             @OA\Property(property="death", type="string", format="date", description="Director's date of death if applicable", example=""),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Director created",
     *     )
     * )
     */

    public function store(StoreDirectorRequest $request)
    {
        $data = $request->validated();
        $directorDTO = new DirectorDTO(...$data);

        $director = $this->directorRepository->create($directorDTO->toArray());
        return $this->showInstance($director, 'director');
    }

    /**
     * @OA\Get(
     *     path="/directors/{id}",
     *     summary="Get details of a specific director",
     *     tags={"Directors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Unique identifier of the director",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success, returns the director details",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Director not found"
     *     )
     * )
     */

    public function show(Director $director)
    {
        $director = $this->directorRepository->find($director->id);
        return $this->showInstance($director, 'director');
    }

    /**
     * @OA\Put(
     *     path="/directors/{id}",
     *     summary="Update an existing director",
     *     tags={"Directors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Unique identifier of the director to be updated",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Partial or full data to update a director. None of the fields are required.",
     *         required=false,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", description="The name of the director"),
     *             @OA\Property(property="image", type="string", description="URL or path to the director's image"),
     *             @OA\Property(property="description", type="string", description="Brief description about the director"),
     *             @OA\Property(property="nationality", type="string", description="The nationality of the director"),
     *             @OA\Property(property="birth", type="string", format="date", description="Director's date of birth"),
     *             @OA\Property(property="death", type="string", format="date", description="Director's date of death if applicable")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success, returns updated director details",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="No data provided for update"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Director not found"
     *     )
     * )
     */

    public function update(UpdateDirectorRequest $request, Director $director)
    {
        $data = $request->validated();

        if (empty($data)) {
            return $this->errorResponse('No data provided for update', 400);
        }

        $directorDTO = new DirectorDTO(...$data);
        $director = $this->directorRepository->update($directorDTO->toArray(), $director->id);
        return $this->showInstance($director, 'director');
    }

    /**
     * @OA\Delete(
     *     path="/directors/{id}",
     *     summary="Deletes a director",
     *     tags={"Directors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Unique identifier of the director to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="director deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="director not found"
     *     )
     * )
     */
    public function destroy(Director $director)
    {
        $this->directorRepository->delete($director->id);
        return $this->successResponse('Director deleted successfully');
    }
}
