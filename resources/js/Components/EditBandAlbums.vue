<script setup>
import SectionHeading from "@/Components/Forms/SectionHeading.vue";
defineProps(['albums']);
</script>

<template>
    <div>
        <SectionHeading>
            Albums
        </SectionHeading>
        <div class="">
            <div v-for="album in albums" class="p-3 border-gray-600 flex gap-4 items-center">
                <img :src="album.ImagePath" class="w-8 h-8 rounded" />
                <a :href="'/admin/albums/' + album.ID">{{ album.Title }}</a>
            </div>
        </div>
    </div>
</template>
