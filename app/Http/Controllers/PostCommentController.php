<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Http\Resources\Comment as CommentResource;
use App\Jobs\NotifyUserPostWasCommented;
use App\Mail\CommentPosted;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }
    public function index(BlogPost $post)
    {
        return CommentResource::collection($post->comments()->with('user')->get());
        // return $post->comments()->with('user')->get();
    }
    public function store(BlogPost $post, StoreComment $request)
    {
        $comment = $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id,
        ]);

        // $newComment = \App\Models\Comment::find($comment->id);

        Log::info(var_export($post->user->id, 1));
        Log::info(var_export($comment->id, 1));
        // Log::info(var_export($comment->user->image->url(), 1));
        try {
            // befofre trying to queue it
            // Mail::to($post->user)->send(
            //     new CommentPosted($newComment)
            // );
            // this is first way of adding to queue
            Mail::to($post->user)->queue(
                new CommentPosted($comment)
            );
            NotifyUserPostWasCommented::dispatch($comment);
            // here we specify when we wanted it in the queue
            // $when = now()->addMinutes(1);
            // Mail::to($post->user)->later($when,
            //     new CommentPosted($newComment)
            // );

        } catch (\Exception$e) {
            Log::info($e->getMessage());
        }
        // $request->session()->flash('status', 'Comment was created');
        // return redirect()->back();
        return redirect()->back()->withStatus('Comment was created!');

    }
}
