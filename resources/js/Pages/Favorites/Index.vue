<script setup>
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import Page from "@/Components/Page.vue";
import Song from "@/Components/Song.vue";
import Player, { PlaylistSource } from "@/PlayerStore.js";

const props = defineProps(['songs']);

// When this page loads, we'll set the Player's custom playlist to our favorites
onMounted(() => {
    if (props.songs && props.songs.length > 0) {
        Player.setCustomPlaylist(props.songs);
    }
});

const playSong = (song) => {
    // When playing from the favorites page, set the playlist source to CUSTOM
    // and provide the favorites list as the custom playlist
    Player.playSong(song, PlaylistSource.CUSTOM, props.songs);
};
</script>

<template>
    <Head title="My Favorites" />

    <Page>
        <h1 class="text-2xl font-bold mb-6">My Favorites</h1>

        <div v-if="songs.length > 0">
            <div class="bg-gray-900 rounded-lg p-4">
                <Song 
                    v-for="song in songs" 
                    :key="song.ID" 
                    :song="song"
                    class="mb-2"
                    @play-song="playSong"
                />
            </div>
        </div>
        <div v-else class="text-center p-8 text-gray-400">
            You haven't favorited any songs yet. Find songs you like and click the star icon to add them to your favorites.
        </div>
    </Page>
</template>