<script setup>
import AlbumCard from "@/Components/AlbumCard.vue";

defineProps(['albums'])
</script>

<template>
    <div class="p-0 md:p-3 rounded-lg">
        <template v-for="album in albums">
            <AlbumCard
                v-if="album.songs.length"
                :album="album"
                @copy-song-to-clipboard="(song_id) => $emit('copy-song-to-clipboard', song_id)"
            ></AlbumCard>
        </template>
    </div>
</template>
