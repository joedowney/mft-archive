<script setup>
import { Link } from '@inertiajs/vue3';
import Search from "@/Components/Search.vue";
import BackButton from "@/Components/BackButton.vue";
import AudioPlayer from "@/Components/AudioPlayer.vue";
</script>

<template>
    <div class="flex gap-4 mb-6 my-4 items-center max-w-4xl w-full mx-auto px-4 lg:px-0">
        <Link href="/" class="p-3 flex items-center bg-gray-800 rounded-full h-10 w-10">
            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512" style="fill:#ffffff"><path d="M303.5 5.7c-9-7.6-22.1-7.6-31.1 0l-264 224c-10.1 8.6-11.3 23.7-2.8 33.8s23.7 11.3 33.8 2.8L64 245.5V432c0 44.2 35.8 80 80 80H432c44.2 0 80-35.8 80-80V245.5l24.5 20.8c10.1 8.6 25.3 7.3 33.8-2.8s7.3-25.3-2.8-33.8l-264-224zM112 432V204.8L288 55.5 464 204.8V432c0 17.7-14.3 32-32 32H384V312c0-22.1-17.9-40-40-40H232c-22.1 0-40 17.9-40 40V464H144c-17.7 0-32-14.3-32-32zm128 32V320h96V464H240z"/></svg>
        </Link>
        <BackButton></BackButton>
        <Search class="ml-6" />
    </div>
    <slot />
    <AudioPlayer></AudioPlayer>
</template>
