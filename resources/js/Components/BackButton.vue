<script setup>

import {router} from "@inertiajs/vue3";
import {ref} from "vue";

let stack = ref([]);
router.on('navigate', (event) => {
    stack.value.push(event.detail.page.url);
});
let back = () => {
    stack.value.pop();
    stack.value.pop();
    window.history.back();
}
</script>

<template>
    <a v-if="stack.length > 1" href="#" @click.prevent="back" class="p-3 flex items-center bg-gray-800 rounded-full h-10 w-10">
        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512" style="fill:#ffffff"><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/></svg>
    </a>
    <span v-else class="p-3 flex items-center bg-gray-800 rounded-full h-10 w-10">
        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512" style="fill:#7e8592"><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/></svg>
    </span>
</template>
