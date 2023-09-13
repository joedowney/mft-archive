<script setup>
import {Head, Link} from "@inertiajs/vue3";
import Page from "@/Components/Page.vue";
let props = defineProps(['cities', 'states']);
let citiesByState = (state_id) => {
    return props.cities.filter((city) => city.StateID === state_id);
}
</script>

<template>
    <Head title="Cities"></Head>

    <Page>
        <div v-for="state in states" class="mb-10">
            <h2 class="mb-1">{{ state.State }}</h2>
            <div class="grid grid-cols-3 gap-4">
                <template v-for="city in citiesByState(state.ID)">
                    <Link v-if="city.bands_count"
                          :href="'/cities/' + city.Slug"
                          class="bg-gray-800 p-4 rounded-lg hover:bg-gray-700"
                    >
                        <h2>{{ city.City }}</h2>
                        <div class="text-sm text-gray-400">{{ city.bands_count }} bands</div>
                    </Link>
                </template>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <template v-for="city in cities">
                <Link v-if="city.bands_count"
                      :href="'/cities/' + city.Slug"
                      class="bg-gray-800 p-4 rounded-lg hover:bg-gray-700"
                >
                    <h2>{{ city.City }}</h2>
                    <div class="text-sm text-gray-400">{{ city.bands_count }} bands</div>
                </Link>
            </template>
        </div>
    </Page>
</template>
