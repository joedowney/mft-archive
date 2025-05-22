<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import Page from "@/Components/Page.vue";
import Song from "@/Components/Song.vue";
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    playlist: Object,
    songs: Array
});

const form = useForm({
    name: props.playlist.name,
    description: props.playlist.description || '',
    is_public: props.playlist.is_public,
});

const deleteModalOpen = ref(false);
const reordering = ref(false);
const songList = ref([...props.songs]);
const submitting = ref(false);

const submit = () => {
    form.patch(route('playlists.update', props.playlist), {
        preserveScroll: true,
    });
};

const deletePlaylist = () => {
    router.delete(route('playlists.destroy', props.playlist), {
        onBefore: () => {
            return confirm('Are you sure you want to delete this playlist?');
        },
    });
};

const removeSong = async (song) => {
    if (confirm('Remove this song from the playlist?')) {
        try {
            const response = await axios.delete(route('playlists.songs.remove', props.playlist), {
                data: { song_id: song.ID }
            });
            
            if (response.data.success) {
                // Remove from song list
                songList.value = songList.value.filter(s => s.ID !== song.ID);
            }
        } catch (error) {
            console.error('Error removing song:', error);
        }
    }
};

const startReordering = () => {
    reordering.value = true;
};

const cancelReordering = () => {
    // Reset the song list to original order
    songList.value = [...props.songs];
    reordering.value = false;
};

const moveSongUp = (index) => {
    if (index > 0) {
        const songs = [...songList.value];
        const temp = songs[index];
        songs[index] = songs[index - 1];
        songs[index - 1] = temp;
        songList.value = songs;
    }
};

const moveSongDown = (index) => {
    if (index < songList.value.length - 1) {
        const songs = [...songList.value];
        const temp = songs[index];
        songs[index] = songs[index + 1];
        songs[index + 1] = temp;
        songList.value = songs;
    }
};

const saveOrder = async () => {
    submitting.value = true;
    try {
        const response = await axios.put(route('playlists.songs.reorder', props.playlist), {
            songs: songList.value.map(song => song.ID)
        });
        
        if (response.data.success) {
            reordering.value = false;
        }
    } catch (error) {
        console.error('Error saving song order:', error);
    } finally {
        submitting.value = false;
    }
};
</script>

<template>
    <Head :title="`Edit - ${playlist.name}`" />

    <Page>
        <div class="flex items-center mb-6">
            <a href="#" @click.prevent="$inertia.visit(route('playlists.show', playlist))" class="p-3 flex items-center bg-gray-800 rounded-full h-10 w-10 mr-4 hover:bg-gray-700 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512" style="fill:#ffffff"><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/></svg>
            </a>
            <h1 class="text-2xl font-bold mb-0">Edit Playlist</h1>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 shadow-lg mb-6">
            <form @submit.prevent="submit" class="space-y-6">
                <div class="mb-5">
                    <label for="name" class="block mb-2 text-sm font-medium text-white">Playlist Name</label>
                    <input
                        id="name"
                        type="text"
                        class="border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500"
                        v-model="form.name"
                        required
                        autofocus
                    />
                    <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                </div>

                <div class="mb-5">
                    <label for="description" class="block mb-2 text-sm font-medium text-white">Description (optional)</label>
                    <textarea
                        id="description"
                        class="border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500"
                        v-model="form.description"
                        rows="3"
                    ></textarea>
                    <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</div>
                </div>

                <div class="flex items-center">
                    <input
                        id="is_public"
                        type="checkbox"
                        v-model="form.is_public"
                        class="w-4 h-4 text-blue-600 rounded focus:ring-blue-600 ring-offset-gray-800 focus:ring-2 bg-gray-700 border-gray-600"
                    />
                    <label for="is_public" class="ml-2 text-sm font-medium text-white">Make this playlist public</label>
                </div>

                <div class="flex justify-between">
                    <button
                        type="button"
                        @click="deleteModalOpen = true"
                        class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-red-600 hover:bg-red-700 focus:ring-red-800"
                    >
                        Delete Playlist
                    </button>
                    <button
                        type="submit"
                        class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800"
                        :disabled="form.processing"
                    >
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Songs section -->
        <div class="my-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Songs</h2>
                <div>
                    <PrimaryButton v-if="!reordering && songList.length > 1" @click="startReordering" class="mr-2">
                        Reorder Songs
                    </PrimaryButton>
                    <div v-if="reordering" class="flex space-x-2">
                        <SecondaryButton @click="cancelReordering">
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton @click="saveOrder" :disabled="submitting">
                            Save Order
                        </PrimaryButton>
                    </div>
                </div>
            </div>

            <div v-if="songList.length > 0" class="bg-gray-900 rounded-lg p-4">
                <div 
                    v-for="(song, index) in songList" 
                    :key="song.ID" 
                    class="flex items-center mb-2"
                >
                    <div v-if="reordering" class="flex items-center mr-2 space-x-1">
                        <button 
                            @click="moveSongUp(index)" 
                            :disabled="index === 0"
                            :class="{ 'opacity-30': index === 0 }"
                            class="p-1 rounded hover:bg-gray-700"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                        </button>
                        <button 
                            @click="moveSongDown(index)" 
                            :disabled="index === songList.length - 1"
                            :class="{ 'opacity-30': index === songList.length - 1 }"
                            class="p-1 rounded hover:bg-gray-700"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>
                    
                    <Song 
                        :song="song"
                        class="flex-grow"
                    />
                    
                    <button 
                        v-if="!reordering"
                        @click="removeSong(song)" 
                        class="ml-2 p-1 rounded text-gray-400 hover:text-red-500 hover:bg-gray-700"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
            <div v-else class="text-center p-8 bg-gray-900 rounded-lg text-gray-400">
                This playlist is empty. Add songs to get started.
            </div>
        </div>

        <!-- Delete Modal -->
        <Modal :show="deleteModalOpen" @close="deleteModalOpen = false">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Delete Playlist</h2>
                <p class="mb-6 text-gray-300">Are you sure you want to delete this playlist? This action cannot be undone.</p>
                
                <div class="flex justify-end space-x-2">
                    <SecondaryButton @click="deleteModalOpen = false">
                        Cancel
                    </SecondaryButton>
                    <DangerButton @click="deletePlaylist">
                        Delete Playlist
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </Page>
</template>