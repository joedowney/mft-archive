<script setup>
import Search from "@/Components/Search.vue";
</script>

<template>
    <div class="max-w-5xl mx-auto h-full flex flex-col px-4">

        <div class="flex gap-4 mb-6">
            <Search />
        </div>

        <slot />
    </div>
</template>
