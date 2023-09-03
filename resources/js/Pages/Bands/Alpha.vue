<script setup>
import { Head, Link } from '@inertiajs/vue3';
defineProps(['letter', 'bands']);
</script>

<template>
    <Head :title="letter + ' - Bands'"></Head>

    <h1>{{ letter }}</h1>

    <ul>
        <li v-for="band in bands">
            <Link :href="'/bands/' + band.URL">{{ band.Name }}</Link>
        </li>
    </ul>
</template>
