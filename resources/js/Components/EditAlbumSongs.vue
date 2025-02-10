<script setup>
import SectionHeading from "@/Components/Forms/SectionHeading.vue";
import draggable from 'vuedraggable';
import { ref, defineComponent } from "vue";
import { router } from '@inertiajs/vue3';

let props = defineProps(['album']);
let localSongs = ref([...props.album.songs]);
let dragOver = ref(false);
let uploading = ref(false);
let uploadProgress = ref([]);
let editingSongId = ref(null);
let editingTitle = ref('');

const updateOrder = () => {
    const songIds = localSongs.value.map(song => song.ID);
    axios.post('/admin/songs/update-order', { ids: songIds, album_id: props.album.ID});
}

const startEditing = (song) => {
    editingSongId.value = song.ID;
    editingTitle.value = song.Title;
}

const cancelEditing = () => {
    editingSongId.value = null;
    editingTitle.value = '';
}

const saveTitle = async (song) => {
    if (!editingTitle.value.trim()) {
        return;
    }

    try {
        const response = await axios.patch(`/admin/songs/${song.ID}`, {
            title: editingTitle.value
        });

        // Update local state
        const songIndex = localSongs.value.findIndex(s => s.ID === song.ID);
        if (songIndex !== -1) {
            localSongs.value[songIndex] = {
                ...localSongs.value[songIndex],
                Title: editingTitle.value
            };
        }

        cancelEditing();
    } catch (error) {
        console.error('Failed to update song title:', error);
        alert('Failed to update song title');
    }
}

const handleDrop = async (e) => {
    e.preventDefault();
    dragOver.value = false;

    const files = Array.from(e.dataTransfer.files).filter(file =>
        file.type.startsWith('audio/')
    );

    if (files.length === 0) return;

    uploading.value = true;
    uploadProgress.value = files.map((file, index) => ({
        id: index,
        filename: file.name,
        progress: 0
    }));

    try {
        const uploadPromises = files.map(async (file, index) => {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('album_id', props.album.ID);

            try {
                let response = await axios.post('/admin/songs', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: (progressEvent) => {
                        const percentCompleted = (progressEvent.loaded * 100) / progressEvent.total;
                        updateFileProgress(index, percentCompleted);
                    }
                });
                localSongs.value.push(response.data);
            } catch (error) {
                const fileProgress = uploadProgress.value.find(p => p.id === index);
                if (fileProgress) {
                    fileProgress.error = true;
                    fileProgress.errorMessage = error.response?.data?.message || 'Upload failed';
                }
                throw error;
            }
        });

        await Promise.all(uploadPromises);
    } catch (error) {
        console.error('Upload failed:', error);
    } finally {
        uploading.value = false;
    }
}

const updateFileProgress = (fileId, progress) => {
    const fileProgress = uploadProgress.value.find(p => p.id === fileId);
    if (fileProgress) {
        fileProgress.progress = progress;
    }
}

const deleteSong = async (songId) => {
    if (!confirm('Are you sure you want to delete this song?')) {
        return;
    }

    try {
        await axios.delete(`/admin/songs/${songId}`);
        localSongs.value = localSongs.value.filter(song => song.ID !== songId);
    } catch (error) {
        console.error('Failed to delete song:', error);
        alert('Failed to delete song');
    }
}

defineComponent({
    draggable
})
</script>

<template>
    <div>
        <SectionHeading>
            Songs
        </SectionHeading>

        <!-- Drop Zone -->
        <div
            @dragover.prevent="dragOver = true"
            @dragleave.prevent="dragOver = false"
            @drop="handleDrop"
            class="mb-6 border-2 border-dashed rounded-lg p-8 text-center transition-colors"
            :class="{
                'border-gray-700 bg-gray-900': !dragOver,
                'border-blue-500 bg-gray-800': dragOver
            }"
        >
            <div v-if="!uploading">
                <p class="text-gray-300">
                    Drop audio files here to add songs
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    Accepts MP3, WAV, and M4A files
                </p>
            </div>

            <!-- Upload Progress -->
            <div v-else class="space-y-4">
                <div v-for="file in uploadProgress" :key="file.id" class="w-full">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-300">
                            {{ file.filename }}
                        </span>
                        <span class="text-sm text-gray-400">
                            {{ Math.round(file.progress) }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2.5">
                        <div
                            class="bg-blue-500 h-2.5 rounded-full transition-all duration-300"
                            :style="{ width: `${file.progress}%` }"
                        ></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Songs List -->
        <div class="mb-20">
            <draggable
                v-model="localSongs"
                group="songs"
                @start="() => {}"
                @end="updateOrder"
                item-key="ID"
                handle=".drag-handle"
            >
                <template #item="{element}">
                    <div class="p-3 flex items-center border-b border-gray-600">
                        <div class="drag-handle cursor-move text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M8 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0zM8 12a2 2 0 1 0-4 0 2 2 0 0 0 4 0zM6 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM20 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0zM18 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM18 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                            </svg>
                        </div>

                        <!-- Title Display/Edit Section -->
                        <div class="flex-1 ml-4">
                            <div v-if="editingSongId === element.ID" class="flex items-center space-x-2">
                                <input
                                    type="text"
                                    v-model="editingTitle"
                                    @keyup.enter="saveTitle(element)"
                                    @keyup.esc="cancelEditing"
                                    class="flex-1 bg-gray-800 text-gray-200 px-2 py-1 rounded border border-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none"
                                    ref="titleInput"
                                >
                                <button
                                    @click="saveTitle(element)"
                                    class="text-green-500 hover:text-green-400"
                                    title="Save"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                                <button
                                    @click="cancelEditing"
                                    class="text-gray-500 hover:text-gray-400"
                                    title="Cancel"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div v-else class="flex items-center">
                                <span class="flex-1">{{ element.Title }}</span>
                                <button
                                    @click="startEditing(element)"
                                    class="ml-2 text-gray-400 hover:text-blue-500 transition-colors"
                                    title="Edit title"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button
                            @click="deleteSong(element.ID)"
                            class="ml-4 text-gray-400 hover:text-red-500 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </template>
            </draggable>
        </div>
    </div>
</template>
