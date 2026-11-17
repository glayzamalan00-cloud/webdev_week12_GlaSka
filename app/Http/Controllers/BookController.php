<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        return BookResource::collection($request->user()->books()->paginate(10));
    }

    public function show(Request $request, $id)
    {
        return new BookResource($request->user()->books()->findOrFail($id));
    }

    public function store(StoreBookRequest $request)
    {
        $this->requireWrite($request);

        $book = $request->user()->books()->create($request->validated());

        return (new BookResource($book))->response()->setStatusCode(201);
    }

    public function update(UpdateBookRequest $request, $id)
    {
        $this->requireWrite($request);

        $book = $request->user()->books()->findOrFail($id);
        $book->update($request->validated());

        return new BookResource($book);
    }

    public function destroy(Request $request, $id)
    {
        $this->requireWrite($request);

        $request->user()->books()->findOrFail($id)->delete();

        return response()->json(['message' => 'Book deleted.']);
    }

    private function requireWrite(Request $request): void
    {
        abort_unless($request->user()->tokenCan('books:write'), 403, 'This token is read-only.');
    }
}