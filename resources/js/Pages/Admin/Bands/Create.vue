<script setup>
import { Head } from '@inertiajs/vue3';
import Page from "@/Components/Page.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import NewBandInfoForm from "@/Components/NewBandInfoForm.vue";
defineOptions({ layout:AdminLayout });
let props = defineProps(['cities']);
</script>

<template>
    <Head :title="'New Band'"></Head>

    <Page>
        <h1>New band</h1>
        <NewBandInfoForm :cities="cities" class="mb-20"></NewBandInfoForm>
    </Page>
</template>
