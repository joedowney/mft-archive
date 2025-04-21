import {computed, reactive} from "vue";

export const PlayerState = {
    PAUSED: 'paused',
    PLAYING: 'playing',
    LOADING: 'loading'
}

export const PlaylistSource = {
    ALBUM: 'album',
    FAVORITES: 'favorites',
    CUSTOM: 'custom'
}

let state = reactive({
    current_song: null,
    album: null,
    band: null,
    player_state: PlayerState.STOPPED,
    playlist_source: PlaylistSource.ALBUM,
    custom_playlist: null
});

let playSong = (song, playlistSource = null, customPlaylist = null) => {
    if (state.current_song && song.ID === state.current_song.ID)
        return resume();

    state.current_song = song;
    state.player_state = PlayerState.LOADING;

    // Set playlist source if provided
    if (playlistSource) {
        state.playlist_source = playlistSource;
        
        // If this is a custom playlist, store it
        if (playlistSource === PlaylistSource.CUSTOM && customPlaylist) {
            state.custom_playlist = customPlaylist;
        }
    }

    // For album-based playback, we need to fetch album and band data
    if (state.playlist_source === PlaylistSource.ALBUM) {
        if (!state.band || song.BandID !== state.band?.ID) {
            fetchBand(song.BandID);
        }

        if (!state.album || song.AlbumID !== state.album?.ID) {
            fetchAlbum(song.AlbumID);
        }
    } else {
        // For non-album playback, still fetch album data for display
        if (!state.album || song.album?.ID !== state.album?.ID) {
            state.album = song.album;
        }
        
        // For non-album playback, still fetch band data for display
        if (!state.band || song.album?.band?.ID !== state.band?.ID) {
            state.band = song.album?.band;
        }
    }
};

let resume = () => {
    state.player_state = PlayerState.PLAYING;
}

let fetchAlbum = (album_id) => {
    axios.get('/albums/' + album_id + '/data')
        .then((response) => {
            state.album = response.data;
        });
};

let fetchBand = (band_id) => {
    axios.get('/bands/' + band_id + '/data')
        .then((response) => {
            state.band = response.data;
        });
};

let fetchSong = async (song_id) => {
    let response = await axios.get('/songs/' + song_id + '/data');
    playSong(response.data);
}

let setCurrentSongPlaying = () => {
    state.player_state = PlayerState.PLAYING;
};

let setCurrentSongPaused = () => {
    state.player_state = PlayerState.PAUSED;
}

let toggleCurrentSongPlaying = () => {
    if (state.player_state === PlayerState.PLAYING)
        state.player_state = PlayerState.PAUSED;
    else if (state.player_state === PlayerState.PAUSED)
        state.player_state = PlayerState.PLAYING;
}

let albumPlaylist = computed(() => {
    let songs = [];
    state.band?.albums.forEach((album) => {
        album.songs.forEach((song) => songs.push(song));
    });
    return songs;
});

let currentPlaylist = computed(() => {
    if (state.playlist_source === PlaylistSource.ALBUM) {
        return albumPlaylist.value;
    } else if (state.playlist_source === PlaylistSource.CUSTOM) {
        return state.custom_playlist || [];
    }
    
    // Default to album playlist if source is unknown
    return albumPlaylist.value;
});

let next = computed(() => {
    if (!state.current_song) return null;
    
    let current_song_in_list = currentPlaylist.value.find((song) => song.ID === state.current_song.ID);
    let index = currentPlaylist.value.indexOf(current_song_in_list);
    return currentPlaylist.value[index + 1];
});

let prev = computed(() => {
    if (!state.current_song) return null;
    
    let current_song_in_list = currentPlaylist.value.find((song) => song.ID === state.current_song.ID);
    let index = currentPlaylist.value.indexOf(current_song_in_list);
    return currentPlaylist.value[index - 1];
});

export default {
    playSong: playSong,
    setCurrentSongPlaying: setCurrentSongPlaying,
    setCurrentSongPaused: setCurrentSongPaused,
    toggleCurrentSongPlaying: toggleCurrentSongPlaying,
    currentSong: computed(() => state.current_song),
    playerState: computed(() => state.player_state),
    currentAlbum: computed(() => state.album),
    currentBand: computed(() => state.band),
    playlistSource: computed(() => state.playlist_source),
    setPlaylistSource: (source) => { state.playlist_source = source; },
    setCustomPlaylist: (playlist) => { state.custom_playlist = playlist; },
    next: next,
    prev: prev,
    fetchSong: fetchSong,
    PlaylistSource: PlaylistSource
}
