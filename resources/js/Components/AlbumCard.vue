<script setup>
import Song from "@/Components/Song.vue";

defineProps(['album'])
</script>

<template>
    <div class="mb-12" :id="'album_' + album.ID">
        <div class="flex gap-6 mb-0 md:mb-3">
            <img :src="album.ImagePath" class="w-12 h-12 md:w-16 md:h-16 rounded mt-1.5 md:mt-0" />
            <div>
                <h2 class="mb-1">{{ album.Title }}</h2>
                <p class="text-sm text-gray-400">{{ album.songs_count }} songs</p>
            </div>
        </div>
        <Song v-for="song in album.songs" :song="song"></Song>
    </div>
</template>
