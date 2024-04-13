<script setup>
import SectionHeading from "@/Components/Forms/SectionHeading.vue";
import SubmitButton from "@/Components/Forms/SubmitButton.vue";
import {ref} from "vue";
import {router} from "@inertiajs/vue3";

let props = defineProps(['band']);
let saved = ref(false);
let submitting = ref(false);
let input = ref(null);

function submit() {
    submitting.value = true;
    let data = new FormData();
    data.append('image', input.value.files[0]);
    axios.post('/admin/bands/' + props.band.ID + '/image', data)
        .then(() => {
            saved.value = true;
            router.reload();
            setTimeout(() => saved.value = false, 2000);
        })
        .catch((e) => {})
        .then(() => {
            submitting.value = false;
        });
}

function remove() {
    if (confirm('Are you sure?')) {
        axios.post('/admin/bands/' + props.band.ID + '/image/delete')
            .then(() => {
                router.reload();
            });
    }
}

</script>

<template>
    <form @submit.prevent="submit">
        <SectionHeading>
            Image
        </SectionHeading>

        <div class="flex gap-8">

            <div v-if="band.Image" class="text-center">
                <img :src="band.ImagePath" class="object-cover rounded-lg md:w-32 w-20 h-20 md:h-32"/>
                <a href="#"
                   @click.prevent="remove"
                   class="text-red-600 mt-3 block"
                >remove</a>
            </div>

            <div class="flex gap-4 items-start">

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="image">Upload file</label>
                    <input
                        class="block w-full border rounded-lg cursor-pointer bg-gray-50 text-gray-400 focus:outline-none dark:bg-gray-700 border-gray-600 placeholder-gray-400"
                        type="file"
                        id="image"
                        ref="input"
                        accept="image/*"
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
            </div>


        </div>

    </form>
</template>
