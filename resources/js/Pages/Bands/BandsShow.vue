<script setup>
import { Head, Link } from '@inertiajs/vue3';
defineProps(['band']);
</script>

<template>
    <div class="bg-gray-800 p-4 rounded-lg">
        <h1>{{ band.Name }}</h1>
    </div>
</template>
