<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Page from "@/Components/Page.vue";
defineProps(['alphabet']);
let heading = function(alpha) {
    return alpha === '0' ? '0#$' : alpha;
}
</script>

<template>

    <Head title="Bands" />

    <Page>
        <h1>Bands</h1>

        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            <Link v-for="item in alphabet"
                  :href="'/bands/alpha/' + item.Sort"
                  class="bg-gray-800 p-4 rounded-lg hover:bg-gray-700 block mb-6 md:mb-0"
            >
                <h2>{{ heading(item.Sort) }}</h2>
                <div class="text-sm text-gray-400">{{ item.total }}  bands</div>
            </Link>
        </div>
    </Page>
</template>
