<script setup>
import {reactive, ref} from "vue";
import SectionHeading from "@/Components/Forms/SectionHeading.vue";
let props = defineProps(['album']);
</script>

<template>

    <div>
        <SectionHeading>
            Songs
        </SectionHeading>
        <div class="flex gap-10">
            <a :href="'/admin/albums/' + album.ID + '/songs/new'">+ New Song</a>
        </div>
        <div class="mb-20 mt-10">
            <div v-for="song in album.songs" class="p-3 border-gray-600 flex gap-4 items-center border-b border-gray-200">
                {{ song.Title }}
            </div>
        </div>
    </div>
</template>
