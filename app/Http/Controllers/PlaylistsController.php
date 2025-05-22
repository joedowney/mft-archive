<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PlaylistsController extends Controller
{
    /**
     * Display a listing of the user's playlists.
     */
    public function index()
    {
        $playlists = Auth::user()->playlists()
            ->withCount('songs')
            ->orderBy('created_at', 'desc')
            ->get();

        // Only return JSON for XHR requests that are not from Inertia
        if ((request()->ajax() && !request()->header('X-Inertia')) || request()->wantsJson()) {
            return response()->json([
                'playlists' => $playlists
            ]);
        }

        return Inertia::render('Playlists/Index', [
            'playlists' => $playlists
        ]);
    }

    /**
     * Show the form for creating a new playlist.
     */
    public function create()
    {
        return Inertia::render('Playlists/Create');
    }

    /**
     * Store a newly created playlist in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
        ]);

        $playlist = Auth::user()->playlists()->create($validated);

        // If there's an initial song to add
        if ($request->has('song_id')) {
            $playlist->songs()->attach($request->song_id, ['order' => 0]);
        }

        // For AJAX/Inertia XHR requests, return JSON response
        if ((request()->ajax() && !request()->header('X-Inertia')) || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'playlist' => $playlist
            ]);
        }

        return redirect()->route('playlists.show', $playlist);
    }

    /**
     * Display the specified playlist.
     */
    public function show(Playlist $playlist)
    {
        // Allow access to public playlists even for non-logged in users
        // For private playlists, only allow access to the owner
        if (!$playlist->is_public && (!Auth::check() || $playlist->user_id !== Auth::id())) {
            abort(403);
        }

        $playlist->load(['songs.album.band', 'user']);

        return Inertia::render('Playlists/Show', [
            'playlist' => $playlist,
            'songs' => $playlist->songs
        ]);
    }

    /**
     * Show the form for editing the specified playlist.
     */
    public function edit(Playlist $playlist)
    {
        // Check if user is authorized to edit this playlist
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        $playlist->load('songs.album.band');

        return Inertia::render('Playlists/Edit', [
            'playlist' => $playlist,
            'songs' => $playlist->songs
        ]);
    }

    /**
     * Update the specified playlist in storage.
     */
    public function update(Request $request, Playlist $playlist)
    {
        // Check if user is authorized to update this playlist
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
        ]);

        $playlist->update($validated);

        return redirect()->route('playlists.show', $playlist);
    }

    /**
     * Remove the specified playlist from storage.
     */
    public function destroy(Playlist $playlist)
    {
        // Check if user is authorized to delete this playlist
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        $playlist->delete();

        return redirect()->route('playlists.index');
    }

    /**
     * Add a song to a playlist.
     */
    public function addSong(Request $request, Playlist $playlist)
    {
        // Check if user is authorized to update this playlist
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'song_id' => 'required|exists:mft_songs,ID',
        ]);

        // Check if song already exists in playlist
        $exists = $playlist->songs()->where('song_id', $validated['song_id'])->exists();

        if (!$exists) {
            // Get the next order value
            $nextOrder = $playlist->songs()->count();

            // Add song to playlist
            $playlist->songs()->attach($validated['song_id'], ['order' => $nextOrder]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Song already in playlist']);
    }

    /**
     * Remove a song from a playlist.
     */
    public function removeSong(Request $request, Playlist $playlist)
    {
        // Check if user is authorized to update this playlist
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'song_id' => 'required|exists:mft_songs,ID',
        ]);

        // Remove song from playlist
        $playlist->songs()->detach($validated['song_id']);

        // Reorder remaining songs
        $songs = $playlist->playlistSongs()->orderBy('order')->get();

        foreach ($songs as $index => $playlistSong) {
            $playlistSong->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Reorder songs in a playlist.
     */
    public function reorderSongs(Request $request, Playlist $playlist)
    {
        // Check if user is authorized to update this playlist
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'songs' => 'required|array',
            'songs.*' => 'exists:mft_songs,ID',
        ]);

        // Delete existing order
        $playlist->playlistSongs()->delete();

        // Create new order
        foreach ($validated['songs'] as $index => $songId) {
            $playlist->songs()->attach($songId, ['order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get playlists for dropdown list
     */
    public function listForDropdown()
    {
        // Require authentication for this endpoint
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Authentication required',
                'message' => 'You must be logged in to view your playlists'
            ], 401);
        }

        $playlists = Auth::user()->playlists()
            ->withCount('songs')
            ->orderBy('name')
            ->get();

        return response()->json([
            'playlists' => $playlists
        ]);
    }
}
