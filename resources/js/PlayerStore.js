import {computed, reactive} from "vue";

export const PlayerState = {
    STOPPED: 'stopped',
    PLAYING: 'playing',
    LOADING: 'loading'
}

let state = reactive({
    current_song: null,
    album: null,
    player_state: PlayerState.STOPPED
});

export let setCurrentSong = (new_song) => {
    state.current_song = new_song;
}

export let playSong = (song) => {
    setCurrentSong(song);
    state.player_state = PlayerState.LOADING;
    setTimeout(() => {state.player_state = PlayerState.PLAYING }, 1000);
}

export let currentSong = computed(() => state.current_song);
export let currentAlbum = computed(() => state.album);
export let playerState = computed(() => state.player_state);

