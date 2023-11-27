<script setup>
import Song from "@/Components/Song.vue";
import LinkIcon from "@/Components/LinkIcon.vue";
import {ref} from "vue";

defineProps(['album', 'band']);
let emit = defineEmits(['copy-album-link']);
let copied = ref(false);
let copyAlbumLink = (album_id) => {
    emit('copy-album-link', album_id);
    copied.value = true;
    setTimeout(() => copied.value = false, 3000);
}
</script>

<template>
    <div class="mb-12" :id="'album_' + album.ID">
        <div class="flex gap-6 mb-0 md:mb-3">
            <img :src="album.ImagePath" class="w-12 h-12 md:w-16 md:h-16 rounded mt-1.5 md:mt-0" />
            <div class="w-full">
                <div class="flex mb-1 justify-between w-full">
                    <h2 class="mb-0 flex-1">{{ album.Title }}</h2>
                    <div class="relative mr-10">
                        <div
                            v-if="copied"
                            class="absolute -top-8 -left-8 p-2 rounded bg-blue-500 text-xs text-white whitespace-nowrap">
                            URL Copied!
                        </div>
                        <a href="#" @click.prevent="() => copyAlbumLink(album.ID)">
                            <LinkIcon></LinkIcon>
                        </a>
                    </div>
                </div>
                <p class="text-sm text-gray-400">{{ album.songs_count }} songs</p>
            </div>
        </div>
        <Song
            v-for="song in album.songs"
            :song="song" :id="'song_' + song.ID"
            @copy-song-to-clipboard="(song_id) => $emit('copy-song-to-clipboard', song_id)"
        ></Song>
    </div>
</template>
