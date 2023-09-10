<script setup>
import {currentSong, playSong, playerState} from "@/PlayerStore.js";
import {computed} from "vue";
import EqualizerIcon from "@/Components/EqualizerIcon.vue";
import LoadingIcon from "@/Components/LoadingIcon.vue";
import PlayIcon from "@/Components/PlayIcon.vue";

let props = defineProps(['song']);
let isPlaying = computed(() =>  {
    return currentSong.value?.ID === props.song.ID && playerState.value === 'playing';
});
let isLoading = computed(() => {
   return currentSong.value?.ID === props.song.ID && playerState.value === 'loading';
});
</script>

<template>
    <div class="p-3 mb-2 flex items-start items-center rounded-lg" :class="{'bg-gray-800' : currentSong?.ID === song.ID}">

        <EqualizerIcon v-if="isPlaying" class="mr-5 mt-1"></EqualizerIcon>

        <LoadingIcon v-else-if="isLoading" class="mr-5 mt-1"></LoadingIcon>

        <PlayIcon v-else class="mr-5 mt-1 cursor-pointer" @click.prevent="() => playSong(song)"></PlayIcon>

        <div class="font-bold flex-1">{{ song.Title }}</div>

        <div class="text-sm text-gray-400">
            {{ song.Duration }}
        </div>
    </div>
</template>
