import {computed, reactive} from "vue";

export const PlayerState = {
    PAUSED: 'paused',
    PLAYING: 'playing',
    LOADING: 'loading'
}

let state = reactive({
    current_song: null,
    album: null,
    player_state: PlayerState.STOPPED
});

let playSong = (song) => {

    if (state.current_song && song.ID === state.current_song.ID)
        return resume();

    state.current_song = song;
    state.player_state = PlayerState.LOADING;
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

export default {
    playSong: playSong,
    setCurrentSongPlaying: setCurrentSongPlaying,
    setCurrentSongPaused: setCurrentSongPaused,
    toggleCurrentSongPlaying: toggleCurrentSongPlaying,
    currentSong: computed(() => state.current_song),
    playerState: computed(() => state.player_state),
    currentAlbum: computed(() => state.album),
}
