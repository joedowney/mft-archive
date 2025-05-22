<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import { router, useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    song: Object,
});

const isOpen = ref(false);
const menuRef = ref(null);
const buttonRef = ref(null);
const playlists = ref([]);
const loading = ref(false);
const error = ref('');
const showCreateModal = ref(false);

const createForm = useForm({
    name: '',
    description: '',
    is_public: false,
    song_id: props.song.ID,
});

const fetchPlaylists = async () => {
    loading.value = true;
    error.value = '';
    
    try {
        const response = await axios.get('/playlists/dropdown');
        if (response.data && response.data.playlists) {
            playlists.value = response.data.playlists;
        } else {
            error.value = 'Unable to load playlists';
        }
    } catch (err) {
        if (err.response && err.response.status === 401) {
            error.value = 'Please log in to add songs to playlists';
        } else {
            error.value = 'Failed to load playlists';
        }
    } finally {
        loading.value = false;
    }
};

const toggleMenu = () => {
    if (!isOpen.value) {
        fetchPlaylists();
    }
    isOpen.value = !isOpen.value;
};

const closeMenu = () => {
    isOpen.value = false;
};

const addToPlaylist = async (playlistId, playlistName) => {
    try {
        const response = await axios.post(`/playlists/${playlistId}/songs`, {
            song_id: props.song.ID
        });

        if (response.data.success) {
            // Show notification with playlist name
            showNotification(`"${props.song.Title}" added to ${playlistName}`);
            // Close the menu
            isOpen.value = false;
        } else if (response.data.message) {
            showNotification(response.data.message);
        }
    } catch (err) {
        showNotification('Failed to add to playlist', 'error');
    }
};

const createNewPlaylist = () => {
    // Open the create playlist modal
    showCreateModal.value = true;
    isOpen.value = false; // Close the dropdown
};

const submitCreateForm = () => {
    // Store the playlist name before it gets reset
    const playlistName = createForm.name;
    
    // Use axios instead of Inertia form to avoid automatic redirects
    axios.post(route('playlists.store'), createForm.data())
        .then(response => {
            showCreateModal.value = false;
            createForm.reset();
            fetchPlaylists(); // Reload the playlists after creating a new one
            showNotification(`Playlist "${playlistName}" created and song added!`);
        })
        .catch(error => {
            // Handle validation errors
            if (error.response && error.response.data && error.response.data.errors) {
                Object.keys(error.response.data.errors).forEach(field => {
                    createForm.setError(field, error.response.data.errors[field][0]);
                });
            }
            showNotification('Failed to create playlist', 'error');
        });
};

const showNotification = (message, type = 'success') => {
    // Use global toast system
    if (window.showToast) {
        window.showToast(message, type);
    }
};

// Handle clicking outside to close the menu
const handleClickOutside = (event) => {
    if (isOpen.value && menuRef.value && !menuRef.value.contains(event.target) && 
        buttonRef.value && !buttonRef.value.contains(event.target)) {
        isOpen.value = false;
    }
};

// Add/remove event listeners
onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div class="relative">
        <!-- Add to Playlist Button -->
        <button 
            ref="buttonRef"
            @click.stop="toggleMenu" 
            class="w-6 h-6 flex items-center justify-center cursor-pointer hover:opacity-80"
            title="Add to playlist"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </button>
        
        <!-- Dropdown Menu -->
        <div 
            v-if="isOpen" 
            ref="menuRef" 
            class="absolute right-0 mt-2 w-60 bg-gray-800 rounded-md shadow-lg z-50 overflow-hidden"
            @click.stop
        >
            <div class="p-3 border-b border-gray-700">
                <h3 class="text-sm font-semibold text-white truncate">Add "{{ song.Title }}" to:</h3>
            </div>
            
            <div v-if="loading" class="p-4 flex justify-center">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            
            <div v-else-if="error" class="p-4 text-center text-sm text-red-400">
                {{ error }}
            </div>
            
            <div v-else>
                <div class="max-h-60 overflow-y-auto">
                    <div v-if="playlists.length === 0" class="p-4 text-center text-sm text-gray-400">
                        You don't have any playlists yet
                    </div>
                    <button
                        v-for="playlist in playlists"
                        :key="playlist.id"
                        @click="addToPlaylist(playlist.id, playlist.name)"
                        class="w-full text-left px-4 py-2 hover:bg-gray-700 transition-colors duration-150 flex items-center"
                    >
                        <div class="flex-grow">
                            <div class="text-sm text-white truncate">{{ playlist.name }}</div>
                        </div>
                    </button>
                </div>
                
                <div class="p-2 border-t border-gray-700">
                    <a
                        @click="createNewPlaylist"
                        class="flex items-center justify-center px-3 py-2 text-sm text-blue-400 hover:text-blue-300 cursor-pointer transition-colors duration-150"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Create new playlist
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Create Playlist Modal -->
        <Modal :show="showCreateModal" @close="showCreateModal = false">
            <div class="p-6 bg-gray-800 text-white">
                <h2 class="text-xl font-semibold mb-4">Create New Playlist</h2>
                
                <form @submit.prevent="submitCreateForm" class="space-y-4">
                    <div>
                        <label for="playlist-name" class="block text-sm font-medium text-gray-300 mb-1">Playlist Name</label>
                        <input 
                            id="playlist-name"
                            v-model="createForm.name"
                            type="text"
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                            placeholder="My Awesome Playlist"
                            required
                        />
                        <div v-if="createForm.errors.name" class="text-red-500 text-xs mt-1">{{ createForm.errors.name }}</div>
                    </div>
                    
                    <div>
                        <label for="playlist-description" class="block text-sm font-medium text-gray-300 mb-1">Description (optional)</label>
                        <textarea 
                            id="playlist-description"
                            v-model="createForm.description"
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                            rows="2"
                            placeholder="What's this playlist about?"
                        ></textarea>
                        <div v-if="createForm.errors.description" class="text-red-500 text-xs mt-1">{{ createForm.errors.description }}</div>
                    </div>
                    
                    <div class="flex items-center">
                        <input 
                            id="playlist-public"
                            v-model="createForm.is_public"
                            type="checkbox"
                            class="rounded bg-gray-700 border-gray-600 text-blue-600 focus:ring-blue-500"
                        />
                        <label for="playlist-public" class="ml-2 text-sm text-gray-300">Make this playlist public</label>
                    </div>
                    
                    <div class="flex justify-end space-x-2 pt-2">
                        <SecondaryButton @click="showCreateModal = false">Cancel</SecondaryButton>
                        <PrimaryButton 
                            type="submit"
                            :disabled="createForm.processing"
                        >
                            Create Playlist
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </div>
</template>