<?php

namespace App\Http\Controllers\Api;

//import model Post
use App\Models\Post;

use App\Http\Controllers\Controller;

//import resource PostResource
use App\Http\Resources\PostResource;

//import Http request
use Illuminate\Http\Request;

//import facade Validator
use Illuminate\Support\Facades\Validator;

//import facade Storage
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get all posts
        $posts = Post::latest()->paginate(5);

        //return collection of posts as a resource
        return new PostResource(true, 'List Data Posts', $posts);
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [

            'nama_lengkap' => 'required|string|max:255',
            'no_telp' => 'required|string|max:15',
            'email' => 'required|email',
            'jurusan' => 'required',
            'alamat' => 'required|string',
            'pesan' => 'nullable|string',

            // => 'required'
            // => $request->content
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        $post = Post::create([
            'nama_lengkap' => $request->nama_lengkap,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'jurusan' => $request->jurusan,
            'alamat' => $request->alamat,
            'pesan' => $request->pesan,
        ]);

        //return response
        return new PostResource(true, 'Data Post Berhasil Ditambahkan!', $post);
    }

    /**
     * show
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id)
    {
        //find post by ID
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'Data dengan ID ' . $id . ' tidak ditemukan'
            ], 404);
        }

        //return single post as a resource
        return new PostResource(true, 'Detail Data Post!', $post);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'no_telp' => 'required|string|max:15',
            'email' => 'required|email',
            'jurusan' => 'required',
            'alamat' => 'required|string',
            'pesan' => 'required|string',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find post by ID
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'Data dengan ID ' . $id . ' tidak ditemukan'
            ], 404);
        }

        $post->update([
            'nama_lengkap' => $request->nama_lengkap,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'jurusan' => $request->jurusan,
            'alamat' => $request->alamat,
            'pesan' => $request->pesan,
        ]);

        //return response
        return new PostResource(true, 'Data Post Berhasil Diubah!', $post);
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {

        //find post by ID
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'Data dengan ID ' . $id . ' tidak ditemukan'
            ], 404);
        }

        //delete post
        $post->delete();

        //return response
        return new PostResource(true, 'Data Post Berhasil Dihapus!', null);
    }
}