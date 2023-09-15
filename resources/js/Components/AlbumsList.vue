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
            ></AlbumCard>
        </template>
    </div>
</template>
