<script setup>
import {Head, Link} from "@inertiajs/vue3";
import Page from "@/Components/Page.vue";
import BandList from "@/Components/BandList.vue";
import AlbumListHorizontal from "@/Components/AlbumListHorizontal.vue";
defineProps(['q', 'bands', 'albums', 'songs']);
</script>

<template>
    <Head :title="q"></Head>

    <Page>
        <h1>'{{ q }}'</h1>

        <div v-if="bands.length" class="mb-12">
            <BandList :bands="bands" title="Bands"></BandList>
        </div>

        <div v-if="albums.length" class="mb-12">
            <AlbumListHorizontal :albums="albums" title="Albums"></AlbumListHorizontal>
        </div>

        <div v-if="songs.length">
            <h1 class="mb-0 md:ml-3 text-gray-400">Songs</h1>
            <div v-for="song in songs" class="py-3 mb-2 flex items-start items-center">
                <div class="text-white text-xl mr-5 mt-1">
                    <Link :href="'/bands/' + song.album.band.URL + '#song_' + song.ID">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" style="fill:#9ca3af"><path d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm384 0L176 384V128L384 256z"/></svg>
                    </Link>
                </div>
                <div class="font-bold flex-1">
                    <div class="mb-1">
                        <Link :href="'/bands/' + song.album.band.URL + '#song_' + song.ID" class="hover:underline">
                            {{ song.Title }}
                        </Link>
                    </div>
                    <div class="text-xs text-gray-400">
                        <Link :href="'/bands/' + song.album.band.URL + '#album_' + song.album.ID" class="hover:underline">
                            {{ song.album.Title }}
                        </Link>
                        &nbsp;|&nbsp;
                        <Link :href="'/bands/' + song.album.band.URL" class="hover:underline">
                            {{ song.album.band.Name }}
                        </Link>
                    </div>
                </div>
                <div class="text-sm text-gray-400">
                    {{ song.Duration }}
                </div>
            </div>
        </div>

    </Page>
</template>
