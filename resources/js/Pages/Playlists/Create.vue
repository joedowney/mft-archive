<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import Page from "@/Components/Page.vue";

const form = useForm({
    name: '',
    description: '',
    is_public: false,
    song_id: null,
});

// URL parameters
const returnTo = ref('');

// Check URL for song_id parameter and return_to parameter
onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);

    // Get song_id parameter
    const songId = urlParams.get('song_id');
    if (songId) {
        form.song_id = songId;
    }

    // Get return_to parameter
    const returnPath = urlParams.get('return_to');
    if (returnPath) {
        returnTo.value = returnPath;
    }
});

const submit = () => {
    form.post(route('playlists.store'), {
        preserveScroll: true,
        onSuccess: () => {
            // If we have a return path, redirect there
            if (returnTo.value) {
                window.location.href = returnTo.value;
            }
            // Otherwise if it was triggered from the Add to Playlist menu, go back
            else if (form.song_id) {
                window.history.back();
            }
        }
    });
};
</script>

<template>
    <Head title="Create Playlist" />

    <Page>
        <div class="flex items-center mb-6">
            <a href="#" @click.prevent="$inertia.visit(route('playlists.index'))" class="p-3 flex items-center bg-gray-800 rounded-full h-10 w-10 mr-4 hover:bg-gray-700 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512" style="fill:#ffffff"><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/></svg>
            </a>
            <h1 class="text-2xl font-bold mb-0">Create Playlist</h1>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 shadow-lg">
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
                        placeholder="My Awesome Playlist"
                    />
                    <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                </div>

                <div class="mb-5">
                    <label for="description" class="block mb-2 text-sm font-medium text-white">Description (optional)</label>
                    <textarea
                        id="description"
                        class="border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500"
                        v-model="form.description"
                        placeholder="What's this playlist about?"
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

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800"
                        :disabled="form.processing"
                    >
                        Create Playlist
                    </button>
                </div>
            </form>
        </div>
    </Page>
</template>