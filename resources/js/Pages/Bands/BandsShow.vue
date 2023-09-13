<script setup>
import { Head } from '@inertiajs/vue3';
import BandInfoCard from "@/Components/BandInfoCard.vue";
import AlbumsList from "@/Components/AlbumsList.vue";
import Page from "@/Components/Page.vue";
defineProps(['band']);
</script>

<template>
    <Head :title="band.Name" />

    <Page>
        <BandInfoCard :band="band"></BandInfoCard>

        <AlbumsList :albums="band.albums" class="mt-4"></AlbumsList>
    </Page>
</template>
