<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Page from "@/Components/Page.vue";
</script>

<template>
    <Head title="Welcome" />

    <Page>

        <div class="">
            <img src="/img/mft_logo.png?1" class="mx-auto mb-6"/>
        </div>

        <div class="md:grid grid-cols-3 gap-4">
            <Link href="/bands" class="rounded-lg bg-gray-800 p-4 hover:bg-gray-700 block mb-6">
                <h3>Bands</h3>
                <p>Browse bands by name</p>
            </Link>

            <Link href="/cities" class="rounded-lg bg-gray-800 p-4 hover:bg-gray-700 block mb-6">
                <h3>City</h3>
                <p>Browse bands by city</p>
            </Link>

            <Link href="/genres" class="rounded-lg bg-gray-800 p-4 hover:bg-gray-700 block mb-6">
                <h3>Genre</h3>
                <p>Browse bands by genre</p>
            </Link>
        </div>
    </Page>

</template>

