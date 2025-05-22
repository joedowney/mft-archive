<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Page from "@/Components/Page.vue";
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps(['playlists']);

const createPlaylist = () => {
    router.get(route('playlists.create'));
};

const viewPlaylist = (playlist) => {
    router.get(route('playlists.show', playlist));
};
</script>

<template>
    <Head title="My Playlists" />

    <Page>
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold mb-0">My Playlists</h1>
            <PrimaryButton @click="createPlaylist">Create Playlist</PrimaryButton>
        </div>

        <div v-if="playlists.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="playlist in playlists" :key="playlist.id" 
                 class="bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="p-5 cursor-pointer" @click="viewPlaylist(playlist)">
                    <div class="h-40 bg-gray-700 rounded-md flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                    </div>
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="text-xl font-semibold text-white">{{ playlist.name }}</h2>
                        <div
                            class="ml-2 px-2 py-0.5 text-xs rounded-full"
                            :class="playlist.is_public ? 'bg-green-900 text-green-300' : 'bg-gray-700 text-gray-400'"
                        >
                            {{ playlist.is_public ? 'Public' : 'Private' }}
                        </div>
                    </div>
                    <p v-if="playlist.description" class="text-gray-300 text-sm line-clamp-2">
                        {{ playlist.description }}
                    </p>
                </div>
            </div>
        </div>
        
        <div v-else class="text-center p-8 bg-gray-900 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
            </svg>
            <p class="text-gray-400 mb-4">You don't have any playlists yet.</p>
            <PrimaryButton @click="createPlaylist">Create Your First Playlist</PrimaryButton>
        </div>
    </Page>
</template>