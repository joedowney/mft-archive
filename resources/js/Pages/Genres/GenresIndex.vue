<script setup>
import {Head, Link} from "@inertiajs/vue3";
defineProps(['genres']);
</script>

<template>
    <Head title="Genres" />

    <h1>Genres</h1>

    <div class="grid grid-cols-3 gap-4">
        <template v-for="genre in genres">
            <Link v-if="genre.bands_count"
                  :href="'/genres/' + genre.Slug"
                  class="bg-gray-800 p-4 rounded-lg hover:bg-gray-700"
            >
                <h2>{{ genre.Name }}</h2>
                <div class="text-sm text-gray-400">{{ genre.bands_count }}  bands</div>
            </Link>
        </template>
    </div>
</template>
