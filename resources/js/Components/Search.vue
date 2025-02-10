<script setup>
import {ref} from "vue";
import {router} from "@inertiajs/vue3";

let q = ref('');
let search = () => {
    router.visit('/search?q=' + q.value);
}

</script>

<template>
    <div class="relative flex items-center bg-gray-800 text-xs text-gray-900 text-gray-100 w-full rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 xl:ml-4 text-gray-900 dark:text-gray-100" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="10" cy="10" r="7"></circle><line x1="21" y1="21" x2="15" y2="15"></line></svg>
        <input
            placeholder="Search"
            id="headerSearch"
            class="w-full focus:ring-0 rounded-full px-2 py-2 placeholder-gray-400 bg-transparent border-0 text-white"
            v-model="q"
            @keyup.enter="search"
        />
        <label for="headerSearch" class="absolute pointer-events-none opacity-0">Search</label>
    </div>
</template>
