<script setup>
import Player, {PlayerState} from "@/PlayerStore.js";
import {Link} from "@inertiajs/vue3";
import {ref, watch} from "vue";
import Playlist from "@/Components/Playlist.vue";

let audio_el = ref(null);

watch(Player.currentSong, () => {
    loadCurrentSong();
});
watch(Player.playerState, (newState, oldState) => {
    if (newState === PlayerState.PLAYING)
        audio_el.value.play();
    else if (newState === PlayerState.PAUSED)
        audio_el.value.pause();
});

let loadCurrentSong = () => {
    audio_el.value.src = '/songs/' + Player.currentSong.value.ID + '/play';
    audio_el.value.currentTime = 0;
    Player.setCurrentSongPlaying();
};

</script>

<template>
    <div class="h-24 w-full flex gap-6 bg-gray-900 drop-shadow-xl"
         :class="{'hidden': !Player.currentSong.value }"
         style="background: #080b16"
    >
        <div class="w-96 p-4">
            <div v-if="Player.currentAlbum.value" class="flex gap-3">
                <img :src="Player.currentAlbum.value?.ImagePath" class="w-16 h-16 object-cover rounded" />
                <div class="flex flex-col justify-center">
                    <div class="text-sm font-bold mb-1">{{ Player.currentSong.value?.Title }}</div>
                    <div class="text-xs">
                        <Link :href="'/bands/' + Player.currentAlbum.value?.band?.Path">
                            {{ Player.currentAlbum.value?.band?.Name }}
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-1 flex justify-center items-center">
            <div v-show="Player.currentSong.value" class="w-full">
                <div class="flex items-center justify-center">
                    <button
                        class="text-xl"
                        :class="{'text-gray-600': ! Player.prev.value }"
                        :disabled="! Player.prev.value"
                        @click.prevent="Player.playSong(Player.prev.value)"
                    >
                        <i class="fa-solid fa-backward"></i>
                    </button>
                    <button class="mx-8" @click="() => Player.toggleCurrentSongPlaying()" style="font-size: 34px;">
                        <span v-show="Player.playerState.value === PlayerState.PAUSED"><i class="fa-solid fa-circle-play"></i></span>
                        <span v-show="Player.playerState.value === PlayerState.PLAYING"><i class="fa-solid fa-circle-pause"></i></span>
                    </button>
                    <button
                        class="text-xl"
                        :class="{'text-gray-600': ! Player.next.value }"
                        :disabled="! Player.next.value"
                        @click.prevent="Player.playSong(Player.next.value)"
                    >
                        <i class="fa-solid fa-forward"></i>
                    </button>
                </div>
                <div class="w-full flex items-center gap-3">
                    <div class="text-sm">00:00</div>
                    <div class="flex-1 relative h-1 bg-gray-400 rounded">
                        <div class="h-1 bg-gray-100 rounded" style="width: 40%;"></div>
                    </div>
                    <div class="text-sm">00:00</div>
                </div>
                <audio ref="audio_el"></audio>
            </div>
        </div>
        <div class="w-96 p-4 pl-20 flex items-center">
            <Playlist></Playlist>
        </div>
    </div>
</template>
