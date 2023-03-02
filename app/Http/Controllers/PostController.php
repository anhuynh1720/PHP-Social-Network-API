<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Post::all(), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:255',
        ]);

        if($validator->passes()) {
            $post = Post::create([
                'user_id' => $request->user()->id,
                'title' => $request->title,
                'content' => $request->content,
                
            ]);

            return response()->json($post, 200);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json($post, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:255',
        ]);

        if($validator->passes() && $post->user_id == $request->user()->id) {
            $post->title = $request->title;
            $post->content = $request->content;
            $post->save();

            return response()->json($post, 200);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Post $post)
    {
        if ($post->user_id == $request->user()->id) {
            $post->delete();

            return response()->json([
                'message' => 'Success'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed! You trying to delete post belongs to another user'
            ], 400);
        }
    }
}
