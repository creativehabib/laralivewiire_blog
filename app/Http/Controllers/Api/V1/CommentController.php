<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CommentResource;
use App\Models\Admin\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $comments = Comment::query()
            ->with(['user:id,name'])
            ->withCount('replies')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('commentable_type'), fn ($q) => $q->where('commentable_type', $request->string('commentable_type')))
            ->when($request->filled('commentable_id'), fn ($q) => $q->where('commentable_id', $request->integer('commentable_id')))
            ->latest('id')
            ->paginate(max(1, min(100, (int) $request->integer('per_page', 20))))
            ->withQueryString();

        return CommentResource::collection($comments);
    }

    public function show(Comment $comment)
    {
        $comment->load(['user:id,name'])->loadCount('replies');

        return CommentResource::make($comment);
    }
}
