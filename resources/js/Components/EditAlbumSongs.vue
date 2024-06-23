<script setup>
import {reactive, ref} from "vue";
import SectionHeading from "@/Components/Forms/SectionHeading.vue";
import SubmitButton from "@/Components/Forms/SubmitButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
let props = defineProps(['album']);

let form = reactive({
    title: '',
    album_id: props.album.ID
});
let submitting = ref(false);
let errors = ref([]);
let saved = ref(false);
let show_new_song_form = ref(false);

let submit = () => {

};

</script>

<template>

    <div>
        <SectionHeading>
            Songs
        </SectionHeading>
        <div class="flex gap-10">
            <a href="#" @click="() => show_new_song_form = true">+ New Song</a>
            <form @submit.prevent="submit" class="flex-1 flex gap-3" :class="{'hidden': !show_new_song_form}">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>
                    <input
                        class="block w-full border rounded-lg cursor-pointer bg-gray-50 text-gray-400 focus:outline-none dark:bg-gray-700 border-gray-600 placeholder-gray-400"
                        type="file"
                        id="file_input"
                        ref="input"
                        accept="audio/*"
                    />
                </div>
                <div class="flex items-center mt-6">
                    <SubmitButton :disabled="submitting">
                        <span v-if="submitting">Saving ...</span>
                        <span v-else>Update</span>
                    </SubmitButton>
                    <span class="text-green-600 w-6 ml-4" v-if="saved">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#63E6BE" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                    </span>
                </div>
            </form>
        </div>
        <div class="mb-20 mt-10">
            <div v-for="song in album.songs" class="p-3 border-gray-600 flex gap-4 items-center border-b border-gray-200">
                {{ song.Title }}
            </div>
        </div>
    </div>
</template>
