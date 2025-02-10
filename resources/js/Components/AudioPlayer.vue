<script setup>
import Player, {PlayerState} from "@/PlayerStore.js";
import {Link} from "@inertiajs/vue3";
import {computed, onMounted, reactive, ref, watch} from "vue";
import Playlist from "@/Components/Playlist.vue";

let audio_el = ref(null);
let seek_bar = ref(null);
let state = reactive({
    current_time: null,
    duration: null
});

watch(Player.currentSong, () => {
    loadCurrentSong();
});
watch(Player.playerState, (newState) => {
    if (newState === PlayerState.PLAYING) {
        let promise = audio_el.value.play();
        if (promise !== undefined) {
            promise
                .then(() => {})
                .catch(() => {
                    Player.toggleCurrentSongPlaying();
                });
        }
    }
    else if (newState === PlayerState.PAUSED) {
        audio_el.value.pause();
    }
});

let loadCurrentSong = () => {
    audio_el.value.src = '/songs/' + Player.currentSong.value.ID + '/play';
    audio_el.value.currentTime = 0;
    audio_el.value.play();
    Player.setCurrentSongPlaying();
};

onMounted(() => {
    audio_el.value.addEventListener('durationchange', () => {
        state.duration = audio_el.value.duration;
    });
    audio_el.value.addEventListener('ended', () => {
        if (Player.next.value) {
            Player.playSong(Player.next.value);
        }
        else {
            Player.setCurrentSongPaused();
        }
    });
    audio_el.value.addEventListener('timeupdate', () => {
        state.current_time = audio_el.value.currentTime;
    });
    seek_bar.value.addEventListener('input', (e) => {
        audio_el.value.currentTime = e.target.value;
    });
});

let current_time = computed(() => {
    if (!state.current_time) return '0:00';
    let d = new Date(state.current_time * 1000);
    return d.getUTCMinutes().toString().padStart(1, '0') + ':' + d.getUTCSeconds().toString().padStart(2, '0');
});
let duration = computed(() => {
    if (!state.duration) return '0:00';
    let d = new Date(state.duration * 1000);
    return d.getUTCMinutes().toString().padStart(1, '0') + ':' + d.getUTCSeconds().toString().padStart(2, '0');
});

</script>

<template>
    <div class="lg:h-26 w-full lg:flex gap-6 bg-gray-900 drop-shadow-xl"
         :class="{'!hidden': !Player.currentSong.value }"
         style="background: #080b16"
    >
        <div class="lg:w-80 p-5">
            <div v-if="Player.currentAlbum.value" class="flex gap-3">
                <img :src="Player.currentAlbum.value?.ImagePath"
                     class="w-9 h-9 lg:w-16 lg:h-16 object-cover rounded mt-0.5"
                />
                <div class="flex flex-col lg:justify-center flex-1">
                    <div class="text-sm font-bold lg:mb-1 text-sky-400 lg:text-white">
                        {{ Player.currentSong.value?.Title }}
                    </div>
                    <div class="text-xs">
                        <Link :href="'/bands/' + Player.currentAlbum.value?.band?.Path" class="text-sky-600 lg:text-white">
                            {{ Player.currentAlbum.value?.band?.Name }}
                        </Link>
                    </div>
                </div>

                <div class="block lg:hidden">
                    <button class="h-9" @click="() => Player.toggleCurrentSongPlaying()" style="font-size: 24px; color: #38BDF8">
                        <span v-show="Player.playerState.value === PlayerState.PAUSED"><i class="fa-solid fa-play"></i></span>
                        <span v-show="Player.playerState.value === PlayerState.PLAYING"><i class="fa-solid fa-pause"></i></span>
                    </button>
                </div>
            </div>
        </div>
        <div class="flex-1 flex justify-center items-center lg:-mt-2">
            <div class="max-w-2xl w-full pb-6 lg:pb-0">
                <div v-show="Player.currentSong.value" class="w-full">
                    <div class="hidden lg:flex items-center justify-center">
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

                        <div class="inline-block lg:hidden ml-6" style="color: #38BDF8">
                            <button
                                class="text-xl"
                                :class="{'text-gray-600': ! Player.prev.value }"
                                :disabled="! Player.prev.value"
                                @click.prevent="Player.playSong(Player.prev.value)"
                            >
                                <i class="fa-solid fa-backward"></i>
                            </button>
                        </div>
                        <div class="text-sm w-10 text-right text-sky-600 lg:text-white">{{ current_time }}</div>
                        <input
                            type="range"
                            min="0"
                            :max="state.duration"
                            class="w-full flex-1"
                            :value="state.current_time || 0"
                            step="1"
                            ref="seek_bar"
                        />
                        <div class="text-sm w-10 text-sky-600 lg:text-white">{{ duration }}</div>

                        <div class="inline-block lg:hidden mr-6" style="color: #38BDF8">
                            <button
                                class="text-xl"
                                :class="{'text-gray-600': ! Player.next.value }"
                                :disabled="! Player.next.value"
                                @click.prevent="Player.playSong(Player.next.value)"
                            >
                                <i class="fa-solid fa-forward"></i>
                            </button>
                        </div>
                    </div>
                    <audio ref="audio_el"></audio>
                </div>
            </div>
        </div>
        <div class="w-80 p-4 pl-20 hidden lg:flex items-center">
            <Playlist></Playlist>
        </div>
    </div>
</template>
