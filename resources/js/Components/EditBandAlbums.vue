<script setup>
import SectionHeading from "@/Components/Forms/SectionHeading.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SubmitButton from "@/Components/Forms/SubmitButton.vue";
import draggable from 'vuedraggable';
import {reactive, ref, defineComponent} from "vue";
import {router} from "@inertiajs/vue3";

defineComponent(draggable);

let props = defineProps(['albums', 'band']);

let localAlbums = ref([...props.albums]);

let form = reactive({
    title: '',
    band_id: props.band.ID
});
let submitting = ref(false);
let errors = ref([]);
let saved = ref(false);
let show_new_album_form = ref(false);

function submit() {
    submitting.value = true;
    errors = ref([]);
    axios.post('/admin/albums', form)
        .then((response) => {
            saved.value = true;
            setTimeout(() => {
                router.visit('/admin/albums/' + response.data)
            }, 1000);
        })
        .catch((e) => errors = e.response.data.errors)
        .then(() => submitting.value = false);
}

function updateAlbumOrder() {
    let albumIds = localAlbums.value.map(album => album.ID);
    axios.post('/admin/albums/update-order', { ids: albumIds, band_id: props.band.ID });
}

</script>

<template>
    <div>
        <SectionHeading>
            Albums
        </SectionHeading>
        <div class="flex gap-3">
            <a href="#" @click="() => show_new_album_form = true">+ New Album</a>
            <form @submit.prevent="submit" class="flex-1 flex gap-3" :class="{'hidden': !show_new_album_form}">
                <TextInput placeholder="Title" v-model="form.title"></TextInput>
                <SubmitButton>
                    <span v-if="submitting">...</span>
                    <span v-else>Save</span>
                </SubmitButton>
                <span class="text-green-600 w-6 ml-4" v-if="saved">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#63E6BE" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                </span>

            </form>
        </div>
        <draggable
            v-model="localAlbums"
            group="albums"
            @start="() => {}"
            @end="updateAlbumOrder"
            item-key="ID"
            handle=".drag-handle"
            class="mt-6"
        >
            <template #item="{element}">
                <div class="flex items-center gap-3 p-2">
                    <div class="drag-handle cursor-move text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M8 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0zM8 12a2 2 0 1 0-4 0 2 2 0 0 0 4 0zM6 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM20 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0zM18 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM18 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                        </svg>
                    </div>
                    <img :src="element.ImagePath" class="w-8 h-8 rounded" />
                    <a :href="'/admin/albums/' + element.ID">{{ element.Title }}</a>
                </div>
            </template>
        </draggable>
    </div>
</template>
