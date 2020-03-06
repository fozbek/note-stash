<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteStoreRequest;
use App\Repositories\NoteRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use App\Http\Resources\NoteResource;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * @var NoteRepository
     */
    private $noteRepository;

    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $criteria = [
            'user_id' => Auth::id()
        ];
        return $this->noteRepository->findAll($criteria);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param NoteStoreRequest $request
     * @return NoteResource
     */
    public function store(NoteStoreRequest $request)
    {
        $input = array_merge($request->only([
            'name',
            'content',
            'tags',
        ]), [
            'user_id' => Auth::id(),
        ]);

        try {
            $note = $this->noteRepository->create($input);
        } catch (\Exception $exception) {
            return \response()->json('Unprocessable Entity', 422);
        }

        return new NoteResource($note);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return NoteResource
     */
    public function show(int $id)
    {
        $note = $this->noteRepository->findOneBy([
            'id' => $id,
        ]);

        return new NoteResource($note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param NoteStoreRequest $request
     * @param int $id
     * @return NoteResource
     * @throws \Exception
     */
    public function update(NoteStoreRequest $request, int $id)
    {
        $input = $request->only([
            'id',
            'name',
            'content',
            'tags',
        ]);

        try {
            $note = $this->noteRepository->update($input, [
                'id' => $id,
                'user_id' => Auth::id(),
            ]);
        } catch (\Exception $exception) {
            return \response()->json('Unprocessable Entity', 422);
        }

        return new NoteResource($note);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id)
    {
        $this->noteRepository->delete($id);

        return response(null, 204);
    }

}
