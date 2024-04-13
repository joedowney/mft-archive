<script setup>

import Page from "@/Components/Page.vue";
import {usePage, Link} from "@inertiajs/vue3";

let page = usePage();

</script>

<template>
    <page>
        <div class="py-3 flex gap-8 items-center mb-6">
            <Link href="/admin" class="p-3 flex items-center bg-gray-800 rounded-full h-10 w-10">
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512" style="fill:#ffffff"><path d="M303.5 5.7c-9-7.6-22.1-7.6-31.1 0l-264 224c-10.1 8.6-11.3 23.7-2.8 33.8s23.7 11.3 33.8 2.8L64 245.5V432c0 44.2 35.8 80 80 80H432c44.2 0 80-35.8 80-80V245.5l24.5 20.8c10.1 8.6 25.3 7.3 33.8-2.8s7.3-25.3-2.8-33.8l-264-224zM112 432V204.8L288 55.5 464 204.8V432c0 17.7-14.3 32-32 32H384V312c0-22.1-17.9-40-40-40H232c-22.1 0-40 17.9-40 40V464H144c-17.7 0-32-14.3-32-32zm128 32V320h96V464H240z"/></svg>
            </Link>
            <h1 class="flex-1 mb-0">Admin</h1>
            <div class="flex items-center gap-4">
                <span class="text-sm">{{ page.props.auth.user.name }}</span>
                <div class="p-3 flex items-center bg-gray-800 rounded-full h-10 w-10 cursor-pointer" title="Log out">
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em"  viewBox="0 0 512 512" style="fill:#ffffff"><path d="M505 273c9.4-9.4 9.4-24.6 0-33.9L377 111c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l87 87L184 232c-13.3 0-24 10.7-24 24s10.7 24 24 24l246.1 0-87 87c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0L505 273zM168 80c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 32C39.4 32 0 71.4 0 120L0 392c0 48.6 39.4 88 88 88l80 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-80 0c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l80 0z"/></svg>
                </div>
            </div>
        </div>

        <slot />
    </page>
</template>
