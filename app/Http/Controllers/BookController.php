<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    /**
     * Display a listing of the books.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return  BookResource::collection(Book::paginate(10));
    }

    /**
     * Store a newly created book in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validation = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100', 'unique:App\Models\Book,name'],
            'author' => ['required', 'string', 'max:100'],
            'publication_date' => ['required', 'date'],
            'categories' => ['required', 'array'],
            'categories.*' => ['nullable', 'exists:App\Models\Category,id']
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()->first(),
                'data' => $validation->errors(),
            ], 400);
        }

        try {
            $book = Book::create($request->except('categories'));
            $book->attach($request->categories);

            return response()->json([
                'status' => 'success',
                'message' => 'Book updated',
                'data' => null
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => 'error',
                'message' => 'Server error',
                'data' => null
            ], 500);
        }
    }

    /**
     * Display the specified book.
     *
     * @param $book
     * @return JsonResponse
     */
    public function show(Book $book): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'OK',
            'data' => [
                'book' => new BookResource($book)
            ]
        ]);
    }

    /**
     * Update the specified book in storage.
     *
     * @param Request $request
     * @param Book $book
     * @return JsonResponse
     */
    public function update(Request $request, Book $book): JsonResponse
    {
        $validation = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100', Rule::unique('books', 'id')->ignore($book->id)],
            'author' => ['required', 'string', 'max:100'],
            'publication_date' => ['required', 'date'],
            'categories' => ['required', 'array'],
            'categories.*' => ['nullable', 'exists:App\Models\Category,id']
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()->first(),
                'data' => $validation->errors(),
            ], 400);
        }

        try {

            $book->update($request->except('categories'));
            $book->sync($request->categories);

            return response()->json([
                'status' => 'success',
                'message' => 'Book updated',
                'data' => null
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => 'error',
                'message' => 'Server error',
                'data' => null
            ], 500);
        }
    }

    /**
     * Update the specified book in storage.
     *
     * @param Request $request
     * @param Book $book
     * @return JsonResponse
     */
    public function borrow(Request $request, Book $book): JsonResponse
    {
        try {
            $user = $request->user();

            empty($book->user_id) ? $book->user()->associate($user) : $book->user()->dissociate();
            $book->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Book updated',
                'data' => null
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => 'error',
                'message' => 'Server error',
                'data' => null
            ], 500);
        }
    }

    /**
     * Remove the specified book from storage.
     *
     * @param Book $book
     * @return JsonResponse
     */
    public function destroy(Book $book): JsonResponse
    {
        try {
            $book->deleted();

            return response()->json([
                'status' => 'success',
                'message' => 'Book deleted',
                'data' => null
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => 'error',
                'message' => 'Server error',
                'data' => null
            ], 500);
        }

    }
}
