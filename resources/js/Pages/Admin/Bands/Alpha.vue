<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Page from "@/Components/Page.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
defineProps(['letter', 'bands']);
defineOptions({ layout:AdminLayout });
</script>

<template>
    <Head :title="letter + ' - Bands'"></Head>

    <Page>
        <div class="bg-gray-800 p-4 rounded-lg flex gap-8 mb-6">
            <h1 class="mb-0">{{ letter }}</h1>
        </div>

        <ul>
            <li v-for="band in bands" class="flex py-2 mb-3 gap-4 items-center">
                <img :src="band.ImagePath" class="w-12 h-12 object-cover rounded "/>
                <div>
                    <h3><Link :href="'/admin/bands/' + band.ID">{{ band.Name }}</Link></h3>
                    <div class="text-sm text-gray-400">{{ band.albums_count }} albums &nbsp;|&nbsp; {{ band.songs_count }} songs</div>
                </div>
            </li>
        </ul>
    </Page>
</template>
