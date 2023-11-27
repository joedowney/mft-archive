<script setup>

import { Head } from '@inertiajs/vue3';
import BandInfoCard from "@/Components/BandInfoCard.vue";
import AlbumsList from "@/Components/AlbumsList.vue";
import Page from "@/Components/Page.vue";
import BandList from "@/Components/BandList.vue";
import {onMounted} from "vue";
import PlayerStore from "@/PlayerStore.js";

let props = defineProps(['band']);
let copySongToClipboard = async (song_id) => {
    let url = window.location.protocol + '://' + window.location.host + '/bands/' + props.band.URL + '#song_' + song_id;
    await navigator.clipboard.writeText(url);
}
onMounted(async () => {
    checkHashForSong();
    window.addEventListener('hashchange', checkHashForSong, false);
});
let checkHashForSong = () => {
    let hash = window.location.hash;
    if ( ! hash) return;
    if (hash.substring(1, 5) === 'song') {
        let song_id = hash.substring(6);
        PlayerStore.fetchSong(song_id);
    }
}
</script>

<template>
    <Head :title="band.Name" />

    <Page>
        <BandInfoCard :band="band" class="mb-10"></BandInfoCard>

        <div v-if="band.related_bands.length" class="mb-12">
            <BandList :bands="band.related_bands" title="Related Bands"></BandList>
        </div>

        <h1 class="text-gray-400 mb-0 md:ml-3 mb-6">Albums</h1>
        <AlbumsList
            :albums="band.albums"
            @copy-song-to-clipboard="copySongToClipboard"
        ></AlbumsList>
    </Page>
</template>
