<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;
use App\Enums\Auth\PermissionType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GenreController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Genre::class);
        return view('genres.index');
    }

    public function search(Request $request, GenreRepository $repository)
    {
        $this->authorize('viewAny', Genre::class);

        return $repository->search(
            $request->search,
            $request->selected
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Genre::class);
        return view('genres.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Genre::class);

        $validated = $request->validate([
            'name'=>'required|unique:genres,name|max:255',
        ]);
        $genre = Genre::create($validated);
        return redirect()->route('genres.index')->with('flash', ['message' => __('genres.messages.created'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Genre $genre)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Genre $genre)
    {
        $this->authorize('update', $genre);

        return view('genres.edit', [
            'genre' => $genre,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Genre $genre)
    {
        $this->authorize('update', $genre);

        $validated = $request->validate([
            'name' => 'required|unique:genres,name,' . $genre->id . '|max:255',
        ]);

        $genre->update($validated);

        return redirect()->route('genres.index')->with('flash', ['message' => __('genres.messages.updated'), 'type' => 'warning']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre)
    {
        $this->authorize('delete', $genre);

        $genre->delete();

        return redirect()->route('genres.index')->with('flash', ['message' => __('genres.messages.deleted'), 'type' => 'danger']);
    }
}
