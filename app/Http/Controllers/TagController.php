<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return view('tags.index', compact('tags'));
    }

    public function create()
    {
        return view('tags.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tags' => 'required|array',
            'tags.*' => 'required|string'
        ]);

        foreach ($request->tags as $tagName) {
            Tag::firstOrCreate(['name' => $tagName]);
        }

        return redirect()->route('tags.index')->with('success', 'Tags criadas!');
    }

    public function edit(Tag $tag)
    {
        return view('tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate(['name' => 'required|unique:tags,name,' . $tag->id]);

        $tag->update($request->only('name'));

        return redirect()->route('tags.index')->with('success', 'Tag atualizada!');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('tags.index')->with('success', 'Tag removida!');
    }
}