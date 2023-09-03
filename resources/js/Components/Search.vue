<script setup>

</script>

<template>
    <div class="my-4 relative flex items-center bg-transparent text-xs xl:text-base text-gray-900 text-gray-100 w-full border-gray-200 border-gray-700 border rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 xl:ml-4 text-gray-900 dark:text-gray-100" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="10" cy="10" r="7"></circle><line x1="21" y1="21" x2="15" y2="15"></line></svg>
        <input value=""
            placeholder="Search"
            id="headerSearch"
            class="w-full focus:ring-0 rounded-full px-2 xl:px-4 py-2 xl:py-4 placeholder-gray-900 placeholder-gray-100 bg-transparent border-0"
        />
        <label for="headerSearch" class="absolute pointer-events-none opacity-0">Search</label>
    </div>
</template>
