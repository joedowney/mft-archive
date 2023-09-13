<script setup>
import Player from "@/PlayerStore.js";
import {PlayerState} from "@/PlayerStore.js";
import {computed} from "vue";
import EqualizerIcon from "@/Components/EqualizerIcon.vue";
import LoadingIcon from "@/Components/LoadingIcon.vue";
import PlayIcon from "@/Components/PlayIcon.vue";

let props = defineProps(['song']);

let isPlaying = computed(() =>  {
    return Player.currentSong.value?.ID === props.song.ID && Player.playerState.value === PlayerState.PLAYING;
});
let isLoading = computed(() => {
    return Player.currentSong.value?.ID === props.song.ID && Player.playerState.value === PlayerState.LOADING;
});
</script>

<template>
    <div class="p-3 mb-2 flex items-start items-center rounded-lg" :class="{'bg-gray-800' : Player.currentSong.value?.ID === song.ID}">

        <div v-if="isPlaying" class="mr-5 group">
            <EqualizerIcon v-if="isPlaying" class="group-hover:hidden"></EqualizerIcon>
            <div class="hidden group-hover:block text-xl h-6 w-6 text-center cursor-pointer"
                @click="() => Player.setCurrentSongPaused()"
            >
                <span class="text-sky-400">
                    <i class="fa-regular fa-circle-pause"></i>
                </span>
            </div>
        </div>

        <LoadingIcon v-else-if="isLoading" class="mr-5"></LoadingIcon>

        <PlayIcon v-else class="mr-5 cursor-pointer text-lg h-6 w-6 text-center" @click.prevent="() => Player.playSong(song)"></PlayIcon>

        <div class="font-bold flex-1">{{ song.Title }}</div>

        <div class="text-sm text-gray-400" v-if="song.Duration !== '00:00'">
            {{ song.Duration }}
        </div>
    </div>
</template>
