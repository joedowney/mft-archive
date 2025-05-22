<script setup>
import { ref } from 'vue';
import Song from '@/Components/Song.vue';
import axios from 'axios';

const props = defineProps({
    song: Object,
    playlist: Object
});

const emit = defineEmits(['remove-song', 'play-song']);

const isRemoving = ref(false);

const removeSong = async () => {
    if (isRemoving.value) return;
    
    try {
        isRemoving.value = true;
        
        const response = await axios.delete(`/playlists/${props.playlist.id}/songs`, {
            data: { song_id: props.song.ID }
        });
        
        if (response.data.success) {
            // Show success message
            if (window.showToast) {
                window.showToast(`"${props.song.Title}" removed from playlist`);
            }
            
            // Emit event to update the parent component
            emit('remove-song', props.song.ID);
        }
    } catch (error) {
        console.error('Error removing song from playlist:', error);
        if (window.showToast) {
            window.showToast('Failed to remove song from playlist', 'error');
        }
    } finally {
        isRemoving.value = false;
    }
};

const playSong = (song) => {
    emit('play-song', song);
};
</script>

<template>
    <div class="group relative flex items-center">
        <!-- Regular Song Component -->
        <Song 
            :song="song"
            class="flex-grow"
            @play-song="playSong"
        />
        
        <!-- Remove Button -->
        <button 
            v-if="playlist && playlist.user_id === $page.props.auth.user?.id"
            @click.stop="removeSong" 
            class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 p-1 ml-2 text-gray-400 hover:text-red-500 hover:bg-gray-800 rounded-full"
            :disabled="isRemoving"
            title="Remove from playlist"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</template>