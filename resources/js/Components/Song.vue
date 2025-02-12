<script setup>
import Player from "@/PlayerStore.js";
import {PlayerState} from "@/PlayerStore.js";
import {computed, ref} from "vue";
import EqualizerIcon from "@/Components/EqualizerIcon.vue";
import LoadingIcon from "@/Components/LoadingIcon.vue";
import PlayIcon from "@/Components/PlayIcon.vue";
import LinkIcon from "@/Components/LinkIcon.vue";

let props = defineProps(['song']);
let emit = defineEmits(['copy-song-to-clipboard']);

let copied = ref(false);

let isPlaying = computed(() =>  {
    return Player.currentSong.value?.ID === props.song.ID && Player.playerState.value === PlayerState.PLAYING;
});
let isLoading = computed(() => {
    return Player.currentSong.value?.ID === props.song.ID && Player.playerState.value === PlayerState.LOADING;
});
let copySongLink = (song_id) => {
    emit('copy-song-to-clipboard', song_id);
    copied.value = true;
    setTimeout(() => copied.value = false, 3000);
}
</script>

<template>
    <div
        class="p-2 mb-0 md:mb-2 flex items-center rounded-lg group hover:bg-black"
        :class="{'bg-gray-800' : Player.currentSong.value?.ID === song.ID}"
    >

        <div v-if="isPlaying" class="mr-5 group">
            <EqualizerIcon v-if="isPlaying" class="group-hover:hidden"></EqualizerIcon>
            <div class="hidden group-hover:block text-2xl h-8 w-6 text-center cursor-pointer"
                @click="() => Player.setCurrentSongPaused()"
            >
                <span class="text-sky-400">
                    <i class="fa-regular fa-circle-pause"></i>
                </span>
            </div>
        </div>

        <LoadingIcon v-else-if="isLoading" class="mr-5"></LoadingIcon>

        <PlayIcon v-else class="mr-5 cursor-pointer text-2xl h-8 w-6 text-center" @click.prevent="() => Player.playSong(song)"></PlayIcon>

        <div class="font-bold flex-1">{{ song.Title }}</div>

        <div class="text-sm text-gray-400" v-if="song.Duration !== '00:00'">
            {{ song.Duration }}
        </div>

        <div class="text-gray-400 ml-3 mr-1 opacity-1 sm:opacity-0 group-hover:opacity-100 relative">
            <div
                v-if="copied"
                class="absolute -top-10 -left-8 p-2 rounded bg-blue-500 text-xs text-white whitespace-nowrap">
                URL Copied!
            </div>
            <a href="#" @click.prevent="() => copySongLink(song.ID)">
                <LinkIcon></LinkIcon>
            </a>
        </div>
    </div>
</template>
