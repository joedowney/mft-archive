<script setup>
import {Link} from "@inertiajs/vue3";
import {onMounted, ref} from "vue";
defineProps(['albums']);
let scroll_container = ref(null);
let scrollable = ref(null);
let showRightArrow = ref(false);
let showLeftArrow = ref(false);
onMounted(() => {
    if (scroll_container.value.scrollWidth > scroll_container.value.offsetWidth)
        showRightArrow.value = true;
});
let setArrows = () => {
    if (scroll_container.value.scrollWidth <= scroll_container.value.offsetWidth)
        return;

    if (scroll_container.value.scrollLeft) {
        if (!showLeftArrow.value)
            showLeftArrow.value = true;
    }
    else if (showLeftArrow.value) {
        showLeftArrow.value = false;
    }

    if (scroll_container.value.scrollWidth > (scroll_container.value.scrollLeft + scroll_container.value.offsetWidth)) {
        if (!showRightArrow.value)
            showRightArrow.value = true
    }
    else {
        showRightArrow.value = false;
    }
};
</script>

<template>
    <div class="relative">
        <div class="overflow-x-scroll" ref="scroll_container" @scroll="setArrows">
            <div ref="scrollable" class="flex gap-4">
                <Link
                    v-for="album in albums"
                    :href="'/bands/' + album.band.URL + '#album_' + album.ID"
                    class="bg-gray-800 p-4 rounded-lg hover:bg-gray-700 block mb-4 md:mb-0 p-2 text-center"
                >
                    <div class="w-32 md:w-40">
                        <img :src="album.ImagePath" class="w-32 h-32 md:w-40 md:h-40 object-cover rounded" />
                        <h3 class="text-ellipsis overflow-hidden whitespace-nowrap mt-4 mb-0 text-xs md:text-base ">{{ album.Title }}</h3>
                        <h4 class="text-ellipsis overflow-hidden whitespace-nowrap mt-1 mb-0 text-xs md:text-base text-gray-400">{{ album.band.Name }}</h4>
                    </div>
                </Link>
            </div>
        </div>

        <div
            class="absolute h-full w-6 top-0 -left-8 hidden md:flex items-center justify-center text-gray-600"
            style="font-size: 40px;"
            v-if="showLeftArrow"
        >
            <i class="fa-solid fa-chevron-left"></i>
        </div>
        <div
            class="absolute h-full w-6 top-0 -right-8 hidden md:flex items-center justify-center text-gray-600"
            style="font-size: 40px;"
            v-if="showRightArrow"
        >
            <i class="fa-solid fa-chevron-right"></i>
        </div>
    </div>
</template>
