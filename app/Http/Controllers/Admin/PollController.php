<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use Illuminate\Support\Facades\Gate;

class PollController extends Controller
{
    public function index()
    {
        Gate::authorize('poll.view');

        return view('backend.pages.polls.index', [
            'pageTitle' => 'Polls',
        ]);
    }

    public function create()
    {
        Gate::authorize('poll.create');

        return view('backend.pages.polls.create', [
            'pageTitle' => 'Create Poll',
        ]);
    }

    public function edit(Poll $poll)
    {
        Gate::authorize('poll.edit');

        return view('backend.pages.polls.edit', [
            'pageTitle' => 'Edit Poll',
            'poll' => $poll,
        ]);
    }
}
