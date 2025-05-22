<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    song: Object,
});

const isOpen = ref(false);
const menuRef = ref(null);
const buttonRef = ref(null);
const playlists = ref([]);
const loading = ref(false);
const error = ref('');

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
    // Navigate to create playlist page with song ID, adding a return_to parameter
    const currentPath = window.location.pathname + window.location.search;
    window.location.href = `/playlists/create?song_id=${props.song.ID}&return_to=${encodeURIComponent(currentPath)}`;
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
        
    </div>
</template>