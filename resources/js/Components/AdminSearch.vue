<script setup>
import { ref } from "vue";
import axios from "axios";

let q = ref('');
let results = ref([]);
let loading = ref(false);
let error = ref(null);
let hasSearched = ref(false);

const search = async () => {
    if (!q.value.trim()) return;

    loading.value = true;
    error.value = null;
    hasSearched.value = true;

    try {
        const response = await axios.post('/admin/search?q=' + encodeURIComponent(q.value));
        results.value = response.data;
    } catch (e) {
        error.value = 'Failed to fetch search results. Please try again.';
        results.value = [];
    } finally {
        loading.value = false;
    }
}

const clearSearch = () => {
    q.value = '';
    results.value = [];
    hasSearched.value = false;
    error.value = null;
}
</script>

<template>
    <div>
        <div class="relative flex items-center bg-gray-800 text-xs text-gray-900 text-gray-100 w-full rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 xl:ml-4 text-gray-900 dark:text-gray-100" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="10" cy="10" r="7"></circle><line x1="21" y1="21" x2="15" y2="15"></line></svg>
            <input
                placeholder="Search"
                id="adminSearch"
                class="w-full focus:ring-0 rounded-full px-4 py-4 placeholder-gray-400 bg-transparent border-0 text-white"
                v-model="q"
                @keyup.enter="search"
            />
            <button
                v-if="q"
                @click="clearSearch"
                class="absolute right-3 text-gray-400 hover:text-white focus:outline-none"
                aria-label="Clear search">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
            <label for="adminSearch" class="absolute pointer-events-none opacity-0">Search</label>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="mt-4 text-center text-gray-500">
            Loading...
        </div>

        <!-- Error State -->
        <div v-if="error" class="mt-4 text-red-500">
            {{ error }}
        </div>

        <!-- Results -->
        <div v-if="!loading && !error" class="mt-4">
            <div v-if="results.length === 0 && hasSearched" class="text-gray-500">
                No results found for "{{ q }}"
            </div>
            <div v-else-if="hasSearched" class="mb-4 p-4 bg-gray-800 rounded-lg">
                <a :href="'/admin/bands/' + result.ID"
                   v-for="result in results"
                   :key="result.id"
                   class="flex items-center gap-4 py-2 px-4 rounded-lg hover:bg-blue-900"
                >
                    <img :src="result.ImagePath" class="rounded-full w-8 h-8" />
                    <h3>{{ result.Name }}</h3>
                </a>
            </div>
        </div>
    </div>
</template>
