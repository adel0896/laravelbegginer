<?php
// use Illuminate\Http\Request;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UserCommentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Long version of writing it

// Route::get('/', function () {
//     return view('home.index', []);
// })->name('home.index');
// Route::get('/contact', function () {
//  return view('home.contact');
// })->name('home.contact');

// short version of writing it
// Route::view('/', 'home.index')->name('home.index');
// Route::view('/contact', 'home.contact')->name('home.contact');

// long version of writing them but now with controllers
Route::get('/', [HomeController::class, 'home'])
    ->name('home.index');
// ->middleware('auth');
Route::get('/contact', [HomeController::class, 'contact'])
    ->name('home.contact');
Route::get('/secret', [HomeController::class, 'secret'])->name('secret')
    ->middleware('can:home.secret');

Route::get('/single', AboutController::class);

$posts = [
    1 => [
        'title' => 'Intro to Laravel',
        'content' => 'This is a short intro to Laravel',
        'is_new' => true,
        'has_comments' => true,
    ],
    2 => [
        'title' => 'Intro to PHP',
        'content' => 'This is a short intro to PHP',
        'is_new' => false,

    ],
    3 => [
        'title' => 'Intro to smth else',
        'content' => 'This is a short intro to PHP',
        'is_new' => false,

    ],
];

Route::resource('posts', PostsController::class);

// this was before using all thr actions
// ->only(['index', 'show', 'create', 'store', 'edit', 'update']);

// // these two routes handle the posts

// Route::get('/posts', function() use ($posts) {

//     // they read from the website's input
//     // dd(request()->all());
//     // dd((int)request()->query('page', 1));

//     return view('posts.index', ['posts' => $posts]);
// });

// Route::get('/posts/{id}', function($id) use($posts){

//     abort_if(!isset($posts[$id]), 404);
// return view('posts.show', ['post' => $posts[$id]] ) ;
// })->name('posts.show');

// ->where([
//     'id' => '[0-9]+'
// ])

Route::get('/recent-posts/{days_ago?}', function ($daysAgo = 20) {
    return 'Posts from ' . $daysAgo . ' days ago';
})->name('posts.recent.index')->middleware('auth');

// all these routes have a prefix in common so now we will group them to avoid repetition

Route::prefix('/fun')->name('fun.')->group(function () use ($posts) {
    Route::get('responses', function () use ($posts) {
        return response($posts, 201)
            ->header('Content-Type', 'application/json')
            ->cookie('MY_COOKIE', 'Adelina Stefania', 3600);
    })->name('responses');

    // redirects you to that page automaticly
    Route::get('redirect', function () {
        return redirect('/contact');
    })->name('redirect');

    // goes back to the previous route
    Route::get('back', function () {
        return back();
    })->name('back');

    // redirects to a previously named route
    Route::get('named-route', function () {
        return redirect()->route('posts.show', ['id' => 1]);
    })->name('named-route');

    // redirects away from your website
    Route::get('away', function () {
        return redirect()->away('https://google.com');
    })->name('away');

    // gives back json data
    Route::get('json', function () use ($posts) {
        return response()->json($posts);
    })->name('json');

    //downloads a file in the users computer
    Route::get('download', function () use ($posts) {
        return response()->download(public_path('/daniel.jpg'), 'face.jpg');
    })->name('download');

});
Route::resource('posts', PostsController::class);
Route::resource('posts.comments', PostCommentController::class)->only(['index', 'store']);
Route::resource('users', UserController::class)->only(['show', 'edit', 'update']);
Route::resource('users.comments', UserCommentController::class)->only(['store']);
Route::get('/posts/tag/{tag}', 'App\Http\Controllers\PostTagController@index')->name('posts.tags.index');
// Autenthication routes
Auth::routes();
