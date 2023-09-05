<script setup>
import AlbumCard from "@/Components/AlbumCard.vue";

defineProps(['albums'])
</script>

<template>
    <div class="p-3 rounded-lg">
        <AlbumCard v-for="album in albums" :album="album" class="mb-4"></AlbumCard>
    </div>
</template>
