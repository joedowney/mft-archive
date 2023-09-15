<script setup>
import {onMounted, ref} from "vue";

defineProps(['band']);

let description_el = ref(null);
let large_description = ref(false);
let show_more = ref(false);
onMounted(() => {
    if (description_el.value.offsetHeight > 80)
        large_description.value = true;
});
let toggleShowMore = () => {
    show_more.value = !show_more.value;
}
</script>

<template>

    <div class="bg-gray-800 p-4 rounded-lg flex gap-8">
        <img :src="band.ImagePath" class="object-cover rounded-lg md:w-32 w-20 h-20 md:h-32"/>

        <div class="mr-4 flex-1">
            <h1 class="mb-4">{{ band.Name }}</h1>
            <div v-if="band.Members" class="mb-4 text-gray-400">
                <div class="uppercase text-xs text-white font-bold mb-1">Members</div>
                <div class="text-sm">{{ band.Members }}</div>
            </div>
            <div v-show="band.Description">
                <div class="uppercase text-xs text-white font-bold mb-1">About</div>
                <div :class="{'max-h-20 overflow-hidden':!show_more}" class="relative">
                    <div v-html="band.Description" class="text-sm text-gray-400" ref="description_el"></div>
                    <div v-if="!show_more && large_description"
                         class="absolute h-10 bottom-0 w-full"
                         style="background: linear-gradient(180deg, rgba(31,41,54,0) 0%, rgba(31,41,54,1) 100%);"
                    ></div>
                </div>
                <div v-if="large_description" class="mt-0 text-sm">
                    <a href="#" @click.prevent="toggleShowMore">
                        <span v-if="!show_more">more...</span>
                        <span v-else>...less</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
