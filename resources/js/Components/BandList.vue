<script setup>
import {Link} from "@inertiajs/vue3";

defineProps(['bands']);
</script>

<template>
    <div class="flex md:gap-4 overflow-x-scroll">
        <Link v-for="band in bands"
              :href="'/bands/' + band.URL"
              class="rounded-lg hover:bg-gray-700 block mb-4 md:mb-0 p-2 text-center"
        >
            <div class="w-32 md:w-48">
                <img :src="band.ImagePath" class="w-32 h-32 md:w-48 md:h-48 object-cover object-center rounded-full mr-6 md:mr-0" />
                <h3 class="text-ellipsis overflow-hidden whitespace-nowrap md:mt-4 mb-0 text-xs md:text-base mt-2 md:mt-0">
                    {{ band.Name }}
                </h3>
            </div>
        </Link>

    </div>
</template>
