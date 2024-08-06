<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Gate;

class BlogPostController extends Controller
{
    public function index()
    {
        $blogPosts = BlogPost::with('user')->latest()->paginate(3);
        return Inertia::render('Blog/Index', [
            'blogPosts' => $blogPosts
        ]);
    }

    public function create()
    {
        return Inertia::render('Blog/Create');
    }

    //store data
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);
        $blogPost = Auth::user()->blogPosts()->create($request->only('title', 'content'));
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $blogPost->image = $path;
        }
        $blogPost->save();
        return redirect()->route('blog.index')->with('status', 'Blog post created successfully.');

    }




    //generate poem with ai

    public function askai(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|min:5',
        ]);
        $prompt = $request->input('prompt');
        $result = Gemini::geminiPro()->generateContent($prompt);

        $content = $result->text(); // Hello! How can I assist you today?

        return Inertia::render('Blog/Create', [
            'content' => $content
        ]);
    }


    //show 
    public function show(BlogPost $blogPost)
    {
        return Inertia::render('Blog/Show', [
            'blogPost' => $blogPost
        ]);
    }

    public function edit(BlogPost $blogPost)
    {
        Gate::authorize('update', $blogPost);
        return Inertia::render('Blog/Edit', [
            'blogPost' => $blogPost
        ]);
    }
    //update 
    public function update(Request $request, BlogPost $blogPost)
    {
        Gate::authorize('update', $blogPost);
        $post = BlogPost::findOrFail($blogPost->id);
        $post->title = $request->input('title');
        $post->content = $request->input('content');

        //if new image is upload 
        if ($request->hasFile('image')) {
            //if the previous image is located in folder
            if ($post->image && Storage::exists($post->image)) {
                Storage::delete($post->image);
            }
            $path = $request->file('image')->store('images', 'public');
            $post->image = $path;
        }
        $post->save();

        return redirect()->route('blog.index')->with('status', "BLog post updated successfully.");


    }

    //delete
    public function destroy(BlogPost $blogPost)
    {
        Gate::authorize('delete', $blogPost);
        $blogPost->delete();
        return redirect()->route('blog.index')->with('status', "BLog post deleted successfully.");

    }

    //add to fav fun
    public function toggleFavorite(Request $request, BlogPost $blogPost)
    {
        //create auth user variable 
        $user = Auth::user();
        //find fav item for that user 
        $fav = $user->favorites()->where('blog_post_id', $blogPost->id)->first();
        //if fav item exsit 
        if ($fav) {
            //delete or remove item from fav
            $fav->delete();
        }
        //else
        else {
            $user->favorites()->create(['blog_post_id' => $blogPost->id]);
        }
        //add to fav and save in db

    }







    // fav index
    public function favindex(){
        $favorites = Auth::user()->favorites()->with('blogPost')->latest()->paginate(3);
       return Inertia::render('Favorite/Index', [
            'Favorites' => $favorites
        ]);
    }






    // //add to fav
    // public function toggleFavorite(Request $request, BlogPost $blogPost){
    //     $user = Auth::user();
    //     $favorite = $user->favorites()->where('blog_post_id', $blogPost->id)->first();
    //     // if favorite item is included in table 

    //     if($favorite){
    //         // do delete 
    //         $favorite->delete();
    //     }
    //     //else
    //     else{
    //         $user->favorites()->create(['blog_post_id' => $blogPost->id]);
    //     }
    //         //create a favorite item in table
    // }


    // public function favindex(){
    //     $user = Auth::user();
    //     $favorites = $user->favorites()->with('blogPost')->latest()->paginate(3);
    //     return Inertia::render('Favorite/Index', [
    //         'favorites' => $favorites
    //     ]);
    // }
}
