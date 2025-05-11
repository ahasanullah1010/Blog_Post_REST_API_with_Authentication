<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

use App\Http\Resources\PostResource;
use function PHPUnit\Framework\throwException;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       try{
         return response()->json([
            'data' => PostResource::collection(Post::all()),
            'message' => 'success',
            'status' => 200 
        ]);
        } catch(\Throwable $e){
            return response()->json([
                'message' => 'Something went wrong.',
            ], 500);
       }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $data = $request->validated();

        $post = Post::create($data);

        return response()->json([
            'data' => new PostResource($post),
            'message' => 'Post Created Successfully',
            'status' => 201 
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $post = Post::find($id);

        if(!$post){
            return response()->json([
                'message' => 'Post not found',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'data' => new PostResource($post),
            'message' => 'success',
            'status' => 200 
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, string $id)
    {
        $post = Post::find($id);

        if(!$post){
            return response()->json([
                'message' => 'Post not found',
                'status' => 404
            ], 404);
        }

        $data = $request->validated();

        $post->update($data);

        return response()->json([
            'data' => new PostResource($post),
            'message' => 'Post Updated Successfully',
            'status' => 200 
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $post = Post::find($id);

        if(!$post){
            return response()->json([
                'message' => 'Post is not found',
                'status' => 404
                ], 404);
        }

        $post->delete();

        return response()->json([
                'message' => 'Post deleted Successfully',
                'status' => 200
                ], 200);
    }
}
