<script setup>
import { Head } from '@inertiajs/vue3';
import Page from "@/Components/Page.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import EditAlbumInfoForm from "@/Components/EditAlbumInfoForm.vue";
import EditAlbumImageForm from "@/Components/EditAlbumImageForm.vue";
defineOptions({ layout:AdminLayout });
let props = defineProps(['album']);
</script>

<template>

    <Head :title="album.Title"></Head>

    <Page>
        <h1 class="mb-8">Edit Album</h1>

        <EditAlbumInfoForm :album="album"></EditAlbumInfoForm>
        <EditAlbumImageForm :album="album"></EditAlbumImageForm>
    </Page>
</template>
