<script setup>

import { Head } from '@inertiajs/vue3';
import Page from "@/Components/Page.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import EditBandAlbums from "@/Components/EditBandAlbums.vue";
import EditBandNameForm from "@/Components/EditBandNameForm.vue";
import EditBandInfoForm from "@/Components/EditBandInfoForm.vue";
import EditBandImageForm from "@/Components/EditBandImageForm.vue";
import EditBandGenresForm from "@/Components/EditBandGenresForm.vue";
import EditBandRelatedBandsForm from "@/Components/EditBandRelatedBandsForm.vue";
defineOptions({ layout:AdminLayout });

let props = defineProps(['band', 'genres', 'bands', 'cities', 'albums']);

</script>

<template>

    <Head :title="band.Name"></Head>

    <Page>
        <h1 class="mb-8">{{ band.Name }}</h1>

        <EditBandNameForm :band="band" class="mb-20"></EditBandNameForm>
        <EditBandInfoForm :band="band" :cities="cities" class="mb-20"></EditBandInfoForm>
        <EditBandImageForm :band="band" class="mb-20"></EditBandImageForm>
        <EditBandGenresForm :band="band" :genres="genres" class="mb-20"></EditBandGenresForm>
        <EditBandRelatedBandsForm :band="band" :bands="bands" class="mb-20"></EditBandRelatedBandsForm>
        <EditBandAlbums :band="band" :albums="albums" class="mb-20"></EditBandAlbums>
    </Page>
</template>
