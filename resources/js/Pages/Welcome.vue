<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Page from "@/Components/Page.vue";
</script>

<template>
    <Head title="Welcome" />

    <Page>

        <div class="mb-10">
            <img src="/img/mft_logo.png" class="mx-auto w-80"/>
        </div>

        <div class="md:grid grid-cols-3 gap-4 mb-8">
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

        <div class="text-base mt-8 mx-10">
            The Musical Family Tree archive was started in 2004 and serves as a crowd sourced MP3 archive of
            the Indiana music scene with a focus on the late 1970s through the 2010s. The MFT archive contains
            over 1000 musical artists and over 100,000 recordings, almost all of which are not found on
            traditional streaming services. Please explore the archive and contact us at team@musicafamilytree.org
            with any questions. Currently, the archive is not accepting new submissions. MFT is a 501c3 and
            supported by donations. If you enjoy the archive and would like to support its continued existence we
            encourage you to donate via this link (<a href="https://www.musicalfamilytree.org/donate" class="text-blue-400">https://www.musicalfamilytree.org/donate</a>).
        </div>
    </Page>

</template>

