<script setup>
import {currentSong} from "@/PlayerStore.js";
</script>

<template>
    <div class="h-20 w-full fixed bottom-0 left-0 flex p-4 bg-gray-900 drop-shadow-xl" style="background: #080b16">
        {{ currentSong }}
    </div>
</template>
