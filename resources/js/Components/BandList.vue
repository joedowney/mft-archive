<script setup>
import {Link} from "@inertiajs/vue3";
import {onBeforeUnmount, onMounted, ref, watch} from "vue";
defineProps(['bands', 'title']);
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
        <h1 class="mb-0 md:ml-3 text-gray-400">{{ title }}</h1>
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
        <div class="grid grid-cols-3 sm:grid-cols-5 md:gap-4 overflow-hidden" ref="grid_el">
            <Link v-for="band in bands"
                  :href="'/bands/' + band.URL"
                  class="rounded-lg hover:bg-gray-700 block p-2 text-center"
            >
                <div>
                    <img :src="band.ImagePath" class="w-full aspect-square object-cover object-center rounded-full mr-6 md:mr-0" />
                    <h3 class="text-ellipsis overflow-hidden whitespace-nowrap md:mt-4 mb-0 text-xs md:text-base mt-2">
                        {{ band.Name }}
                    </h3>
                </div>
            </Link>
        </div>
    </div>

</template>
