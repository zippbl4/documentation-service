<?php

namespace App\Notes\Controllers;

use App\Notes\Entities\Note;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotesController
{
    public function index(Request $request)
    {
        $notes = Note::query()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Notes/Index', ['notes' => $notes]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'nullable',
            'is_markdown' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();

        $note = Note::create($validated);

        return redirect()->back();
    }

    public function update(Request $request, Note $note)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'nullable',
            'is_markdown' => 'boolean',
        ]);

        $note->update($validated);

        return redirect()->back();
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return redirect()->back();
    }
}
