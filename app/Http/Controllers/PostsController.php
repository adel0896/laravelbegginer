<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use App\Models\Image;
// use Illuminate\Http\File;
// use Illuminate\Http\FileHelpers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    // use FileHelpers;
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }
    // private $posts = [
    //     1 => [
    //         'title' => 'Intro to Laravel',
    //         'content' => 'This is a short intro to Laravel',
    //         'is_new' => true,
    //         'has_comments' => true,
    //     ],
    //     2 => [
    //         'title' => 'Intro to PHP',
    //         'content' => 'This is a short intro to PHP',
    //         'is_new' => false,

    //     ],
    //     3 => [
    //         'title' => 'Intro to smth else',
    //         'content' => 'This is a short intro to PHP',
    //         'is_new' => false,

    //     ],
    // ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // DB::enableQueryLog();

        // $posts = BlogPost::with('comments')->get();
        // foreach ($posts as $post) {
        //     foreach ($post->comments as $comment) {
        //         echo $comment->content;
        //     }
        // }

        // dd(DB::getQueryLog());

// this was moved after in a composer
        // $mostCommented = Cache::tags(['blog-post'])->remember('blog-post-commented', 60, function () {
        //     return BlogPost::mostCommented()->take(5)->get();
        // });
        // $mostActive = Cache::remember('users-most-active', 60, function () {
        //     return User::withMostBlogPosts()->take(5)->get();
        // });
        // $mostActiveLastMonth = Cache::remember('users-most-active-last-month', 60, function () {
        //     return User::withMostBlogPostsLastMonth()->take(5)->get();
        // });

        return view('posts.index',
            [
                'posts' => BlogPost::latestWithRelations()->get(),
                // they were here before we moved them to composer
                // 'mostCommented' => $mostCommented,
                // 'mostActive' => $mostActive,
                // 'mostActiveLastMonth' => $mostActiveLastMonth,
                // these were the last monthsueries befor using cache
                // 'mostActive' => User::withMostBlogPosts()->take(5)->get(),
                // 'mostActiveLastMonth' => User::withMostBlogPostsLastMonth()->take(5)->get(),
            ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $this->authorize('posts.create');
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {
        // dd($request);
        $validatedData = $request->validated();
        $validatedData['user_id'] = $request->user()->id;

        // dump($request->hasFile('thumbnail'));
        // die;
        // the long version of creating a new post with the model
        // $post = new BlogPost();
        // $post->title = $validated['title'];
        // $post->content = $validated['content'];
        // $post->save();

        // the short version using static versions

        $post = BlogPost::create($validatedData);

        // dump($hasFile);
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails');
            $post->image()->save(
                Image::make(['path' => $path])
            );
            // dump($file);

            // methods with auto generated names
            // dump($file->getClientMimeType());
            // dump($file->getClientOriginalExtension());
            // dump(Storage::disk('public')->put('thumbnails', $file));

            // $file->store('thumbnails');

            // methods where you can name the files you are storing
            // $name1 = $file->storeAs('thumbnails', $post->id . '.' . $file->guessExtension());
            // $storageVar = Storage::disk('local');
            // $name2 = $storageVar->put('thumbnails/' . $post->id . '.' . $file->guessExtension(), $file);
            // dump(Storage::url($name1));
            // this one does not work
            // dump($storageVar->url($name2));
        }

        $request->session()->flash('status', 'The blog post was created');

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // abort_if(!isset($this->posts[$id]), 404);

        $blogPost = Cache::tags(['blog-post'])->remember("blog-post-{$id}", 60, function () use ($id) {
            return BlogPost::with(['comments' => function ($query) {
                return $query->latest();
            }])
                ->with('tags')
                ->with('user')
                ->with('comments.user')
                ->findOrFail($id);
        });
        $sessionId = session()->getId();
        $counterKey = "blog-post-{$id}-counter";
        $usersKey = "blog-post-{$id}-users";

        $users = Cache::tags(['blog-post'])->get($usersKey, []);
        $usersUpdate = [];
        $difference = 0;
        $now = now();

        foreach ($users as $session => $lastVisit) {
            if ($now->diffInMinutes($lastVisit) >= 1) {
                $difference--;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }
        if (!array_key_exists($sessionId, $users)
            || $now->diffInMinutes($users[$sessionId]) >= 1
        ) {
            $difference++;
        }
        $usersUpdate[$sessionId] = $now;
        Cache::tags(['blog-post'])->forever($usersKey, $usersUpdate);
        if (!Cache::tags(['blog-post'])->has($counterKey)) {
            Cache::tags(['blog-post'])->forever($counterKey, 1);
        } else {
            Cache::tags(['blog-post'])->increment($counterKey, $difference);
        }
        $counter = Cache::tags(['blog-post'])->get($counterKey);
        return view('posts.show', [
            'post' => $blogPost,
            'counter' => $counter,
            // before using cache
            // 'post' => BlogPost::with(['comments' => function ($query) {
            //     return $query->latest();
            // }])->findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        $this->authorize('update', $post);
        return view('posts.edit', ['post' => BlogPost::findOrFail($id)]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);
        // if (Gate::denies('update-post', $post)) {
        //     abort(403, "You can not edit this blog post.");
        // };

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails');

            if ($post->image) {
                Storage::delete($post->image->path);
                $post->image->path = $path;
                $post->image->save();
            } else {

                $post->image()->save(
                    Image::make(['path' => $path])
                );
            }

        }

        // putem sa si stergem update pt ca ia numele functiei si stie sa mearga la policy ul update
        $this->authorize('update', $post);

        $validated = $request->validated();
        $post->fill($validated);
        $post->save();

        $request->session()->flash('status', 'Blog post was updated successfully');

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    public function destroy($id)
    {
        // dd($id);
        $post = BlogPost::findOrFail($id);
        // if (Gate::denies('delete-post', $post)) {
        //     abort(403, "You can not delete this blog post.");
        // };
        $this->authorize('delete', $post);

        $post->delete();

        session()->flash('status', 'Blog post was deleted');

        return redirect()->route('posts.index');

    }
}
