<script setup>
import {Link} from "@inertiajs/vue3";
import {onBeforeUnmount, onMounted, ref} from "vue";
defineProps(['albums', 'title']);
let grid_el = ref(null);
let closed_height = ref(0);
let show_more = ref(false);
let has_more = ref(false);
let setHeight = () => {
    closed_height.value = grid_el.value.firstElementChild.offsetHeight;
    has_more.value = grid_el.value.scrollHeight > closed_height.value;
}

onMounted(() => {
    setHeight();
    window.addEventListener('resize', setHeight);
});
onBeforeUnmount(() => {
    window.removeEventListener('resize', setHeight);
})
</script>

<template>


    <div class="flex justify-between items-center">
        <h1 class="mb-2 md:ml-3 text-gray-400">{{ title }}</h1>
        <a href="#"
           class="font-bold text-sm text-blue-500"
           @click.prevent="() => { show_more = !show_more}"
           v-show="has_more"
        >
            <span v-if="show_more">show less</span>
            <span v-else>show more</span>
        </a>
    </div>


    <div :style="{'height': show_more ? 'auto' : closed_height + 'px'}" class="overflow-hidden">
        <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 md:gap-4 overflow-hidden" ref="grid_el">
            <Link
                v-for="album in albums"
                :href="'/bands/' + album.band.URL + '#album_' + album.ID"
                class=" sm:bg-gray-800 p-2 sm:p-4 rounded-lg hover:bg-gray-700 block text-center"
            >
                <div>
                    <img :src="album.ImagePath" class="w-full aspect-square object-cover rounded" />
                    <h3 class="text-ellipsis overflow-hidden whitespace-nowrap mt-4 mb-0 text-xs md:text-base ">{{ album.Title }}</h3>
                    <h4 class="text-ellipsis overflow-hidden whitespace-nowrap mt-1 mb-0 text-xs md:text-base text-gray-400">{{ album.band.Name }}</h4>
                </div>
            </Link>
        </div>
    </div>
</template>
