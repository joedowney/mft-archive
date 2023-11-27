import {computed, reactive} from "vue";

export const PlayerState = {
    PAUSED: 'paused',
    PLAYING: 'playing',
    LOADING: 'loading'
}

let state = reactive({
    current_song: null,
    album: null,
    band: null,
    player_state: PlayerState.STOPPED
});

let playSong = (song) => {
    if (state.current_song && song.ID === state.current_song.ID)
        return resume();

    state.current_song = song;

    state.player_state = PlayerState.LOADING;

    if (!state.band || song.BandID !== state.band?.ID) {
        fetchBand(song.BandID);
    }

    if (!state.album || song.AlbumID !== state.album?.ID) {
        fetchAlbum(song.AlbumID);
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

let playlist = computed(() => {
    let songs = [];
    state.band?.albums.forEach((album) => {
        album.songs.forEach((song) => songs.push(song));
    });
    return songs;
});

let next = computed(() => {
    let current_song_in_list = playlist.value.find((song) => song.ID === state.current_song.ID);
    let index = playlist.value.indexOf(current_song_in_list);
    return playlist.value[index + 1];
});

let prev = computed(() => {
    let current_song_in_list = playlist.value.find((song) => song.ID === state.current_song.ID);
    let index = playlist.value.indexOf(current_song_in_list);
    return playlist.value[index - 1];
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
    next: next,
    prev: prev,
    fetchSong: fetchSong
}
