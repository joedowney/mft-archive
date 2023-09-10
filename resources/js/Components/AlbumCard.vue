<script setup>
import Song from "@/Components/Song.vue";

defineProps(['album'])
</script>

<template>
    <div class="rounded-lg p-4" :id="'album_' + album.ID">
        <div class="flex gap-6 mb-3">
            <img :src="album.ImagePath" class="w-16 h-16 rounded" />
            <div>
                <h2 class="mb-1">{{ album.Title }}</h2>
                <p class="text-sm text-gray-400">{{ album.songs_count }} songs</p>
            </div>
        </div>
        <Song v-for="song in album.songs" :song="song"></Song>
    </div>
</template>
