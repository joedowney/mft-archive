<script setup>
import { Head } from '@inertiajs/vue3';
import { onMounted, nextTick } from "vue";
import { router } from '@inertiajs/vue3';
import BandInfoCard from "@/Components/BandInfoCard.vue";
import AlbumsList from "@/Components/AlbumsList.vue";
import Page from "@/Components/Page.vue";
import BandList from "@/Components/BandList.vue";
import PlayerStore from "@/PlayerStore.js";

const props = defineProps(['band']);

const copySongToClipboard = async (song_id) => {
    const url = `${window.location.protocol}//${window.location.host}/bands/${props.band.URL}#song_${song_id}`;
    await navigator.clipboard.writeText(url);
}

const scrollToElement = async (elementId) => {
    await nextTick();
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

const checkHash = async () => {
    const hash = window.location.hash;
    if (!hash) return;

    const elementId = hash.slice(1); // Remove the # from the hash
    const type = elementId.split('_')[0];

    if (type === 'song') {
        await scrollToElement(elementId);
        const songId = elementId.split('_')[1];
        PlayerStore.fetchSong(songId);
    } else if (type === 'album') {
        await scrollToElement(elementId);
    }
}

onMounted(() => {
    checkHash();
    window.addEventListener('hashchange', checkHash, false);
    router.on('finish', checkHash);
});
</script>

<template>
    <Head :title="band.Name" />
    <Page>
        <BandInfoCard :band="band" class="mb-10" />
        <div v-if="band.related_bands.length" class="mb-12">
            <BandList :bands="band.related_bands" title="Related Bands" />
        </div>
        <h1 class="text-gray-400 md:ml-3 mb-6">Albums</h1>
        <AlbumsList
            :albums="band.albums"
            @copy-song-to-clipboard="copySongToClipboard"
        />
    </Page>
</template>
