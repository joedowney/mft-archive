<script setup>
import { Head } from '@inertiajs/vue3';
import BandInfoCard from "@/Components/BandInfoCard.vue";
import AlbumsList from "@/Components/AlbumsList.vue";
defineProps(['band']);
</script>

<template>
    <Head :title="band.Name" />

    <BandInfoCard :band="band"></BandInfoCard>

    <AlbumsList :albums="band.albums" class="mt-4"></AlbumsList>
</template>
