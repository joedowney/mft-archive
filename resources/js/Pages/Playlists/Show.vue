<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import Page from "@/Components/Page.vue";
import PlaylistSong from "@/Components/PlaylistSong.vue";
import Player, { PlaylistSource } from "@/PlayerStore.js";

const props = defineProps({
    playlist: Object,
    songs: Array
});

// Use a reactive ref for songs to handle removal
const songList = ref([...props.songs]);

// When this page loads, set the Player's custom playlist to our playlist songs
onMounted(() => {
    if (songList.value && songList.value.length > 0) {
        Player.setCustomPlaylist(songList.value);
    }
});

const playSong = (song) => {
    // When playing from a playlist, set the playlist source to CUSTOM
    // and provide the songs as the custom playlist
    Player.playSong(song, PlaylistSource.CUSTOM, songList.value);
};

// Handler for when a song is removed from the playlist
const handleRemoveSong = (songId) => {
    // Remove the song from our local list
    songList.value = songList.value.filter(song => song.ID !== songId);

    // Update the player's playlist if it's using this playlist
    if (Player.playlistSource.value === PlaylistSource.CUSTOM) {
        Player.setCustomPlaylist(songList.value);
    }
};

const editPlaylist = () => {
    router.get(route('playlists.edit', props.playlist));
};
</script>

<template>
    <Head :title="playlist.name" />

    <Page>
        <div class="flex items-center mb-6">
            <a
                href="#"
                @click.prevent="$page.props.auth.user ? $inertia.visit(route('playlists.index')) : $inertia.visit('/')"
                class="p-3 flex items-center bg-gray-800 rounded-full h-10 w-10 mr-4 hover:bg-gray-700 transition duration-200"
            >
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512" style="fill:#ffffff"><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/></svg>
            </a>
            <h1 class="text-2xl font-bold flex-grow mb-0">{{ playlist.name }}</h1>
            <button
                v-if="$page.props.auth.user && playlist.user_id === $page.props.auth.user.id"
                @click="editPlaylist"
                class="ml-4 text-white bg-gray-800 hover:bg-gray-700 focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center focus:ring-gray-600 transition duration-200"
            >
                Edit Playlist
            </button>
        </div>

        <div class="bg-gray-900 rounded-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row items-start md:space-x-6">
                <div class="w-full md:w-1/4 mb-4 md:mb-0">
                    <div class="h-48 w-48 bg-gray-800 rounded-md flex items-center justify-center mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                    </div>
                </div>
                <div class="flex-grow">
                    <div class="flex items-center mb-1">
                        <p class="text-sm text-gray-400 m-0">Playlist</p>
                        <div
                            class="ml-2 px-2 py-0.5 text-xs rounded-full"
                            :class="playlist.is_public ? 'bg-green-900 text-green-300' : 'bg-gray-700 text-gray-400'"
                        >
                            {{ playlist.is_public ? 'Public' : 'Private' }}
                        </div>
                    </div>
                    <h2 class="text-3xl font-bold mb-2">{{ playlist.name }}</h2>
                    <p v-if="playlist.description" class="text-gray-300 mb-4">{{ playlist.description }}</p>
                    <p class="text-sm text-gray-400">
                        Created by {{ playlist.user.name }}
                    </p>
                </div>
            </div>
        </div>

        <div v-if="songList.length > 0" class="bg-gray-900 rounded-lg p-4">
            <PlaylistSong
                v-for="song in songList"
                :key="song.ID"
                :song="song"
                :playlist="playlist"
                class="mb-2"
                @play-song="playSong"
                @remove-song="handleRemoveSong"
            />
        </div>
        <div v-else class="text-center p-8 bg-gray-900 rounded-lg text-gray-400">
            This playlist is empty. Add songs to get started.
        </div>
    </Page>
</template>