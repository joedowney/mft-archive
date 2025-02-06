<script setup>

import { Head, Link } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Page from "@/Components/Page.vue";
import AdminSearch from "@/Components/AdminSearch.vue";
defineOptions({ layout:AdminLayout });
defineProps(['alphabet']);
let heading = function(alpha) {
    return alpha === '0' ? '0#$' : alpha;
}

</script>

<template>
    <Head title="Dashboard"></Head>

    <page>

        <div class="my-8">
            <AdminSearch></AdminSearch>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            <Link href="/admin/bands/new"
                  class="bg-gray-800 p-4 rounded-lg hover:bg-gray-700 block mb-6 md:mb-0 flex items-center justify-center"
            >
                <div style="font-size: 48px;" class="text-gray-400">&plus;</div>
            </Link>
            <Link v-for="item in alphabet"
                  :href="'/admin/bands/alpha/' + item.Sort"
                  class="bg-gray-800 p-4 rounded-lg hover:bg-gray-700 block mb-6 md:mb-0"
            >
                <h2>{{ heading(item.Sort) }}</h2>
                <div class="text-sm text-gray-400">{{ item.total }}  bands</div>
            </Link>
        </div>
    </page>


</template>
