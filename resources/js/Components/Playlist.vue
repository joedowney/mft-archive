<script setup>
import PlayerStore from "@/PlayerStore.js";
let goNext = () => {
    PlayerStore.playSong(PlayerStore.next.value);
}
</script>

<template>
    <div v-show="PlayerStore.next.value" class="flex gap-3 items-center">
        <a href="#" @click.prevent="goNext" class="text-2xl">
            <i class="fa-regular fa-circle-play"></i>
        </a>
        <a href="#" @click.prevent="goNext">
            <div class="text-xs uppercase font-bold" style="font-size: 10px">Next</div>
            <div class="text-sm">{{ PlayerStore.next.value?.Title }}</div>
        </a>
    </div>
</template>
