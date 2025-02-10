<script setup>
import { Head, Link  } from '@inertiajs/vue3';
import Page from "@/Components/Page.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import EditAlbumInfoForm from "@/Components/EditAlbumInfoForm.vue";
import EditAlbumImageForm from "@/Components/EditAlbumImageForm.vue";
import EditAlbumSongs from "@/Components/EditAlbumSongs.vue";
defineOptions({ layout:AdminLayout });
let props = defineProps(['album']);
</script>

<template>

    <Head :title="album.Title"></Head>

    <Page>
        <h1 class="mb-2">Edit Album</h1>
        <div class="mb-8">
            <Link :href="'/admin/bands/' + album.BandID">
                Back
            </Link>
        </div>

        <EditAlbumInfoForm :album="album" class="mt-12"></EditAlbumInfoForm>
        <EditAlbumImageForm :album="album" class="mt-12"></EditAlbumImageForm>
        <EditAlbumSongs :album="album" class="mt-12"></EditAlbumSongs>
    </Page>
</template>
