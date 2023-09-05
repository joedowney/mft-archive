<script setup>
import { Head, Link } from '@inertiajs/vue3';
defineProps(['letter', 'bands']);
</script>

<template>
    <Head :title="letter + ' - Bands'"></Head>

    <div class="bg-gray-800 p-4 rounded-lg flex gap-8 mb-6">
        <h1 class="mb-0">{{ letter }}</h1>
    </div>

    <ul>
        <li v-for="band in bands" class="flex py-2 mb-3 gap-4 items-center">
            <img :src="band.ImagePath" class="w-12 h-12 object-cover rounded "/>
            <div>
                <h3><Link :href="'/bands/' + band.URL">{{ band.Name }}</Link></h3>
                <div class="text-sm text-gray-400">{{ band.AlbumCount }} albums &nbsp;|&nbsp; {{ band.SongCount }} songs</div>
            </div>
        </li>
    </ul>
</template>
