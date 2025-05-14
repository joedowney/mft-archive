<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import Search from "@/Components/Search.vue";
import BackButton from "@/Components/BackButton.vue";
import AudioPlayer from "@/Components/AudioPlayer.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { ref } from 'vue';

const page = usePage();
const user = page.props.auth?.user;
const userDropdown = ref(null);
</script>

<template>
    <div class="flex gap-4 mb-6 my-4 items-center max-w-4xl w-full mx-auto px-4 lg:px-0">
        <Link href="/" class="p-3 flex items-center bg-gray-800 rounded-full h-10 w-10 hover:bg-gray-700 transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512" style="fill:#ffffff"><path d="M303.5 5.7c-9-7.6-22.1-7.6-31.1 0l-264 224c-10.1 8.6-11.3 23.7-2.8 33.8s23.7 11.3 33.8 2.8L64 245.5V432c0 44.2 35.8 80 80 80H432c44.2 0 80-35.8 80-80V245.5l24.5 20.8c10.1 8.6 25.3 7.3 33.8-2.8s7.3-25.3-2.8-33.8l-264-224zM112 432V204.8L288 55.5 464 204.8V432c0 17.7-14.3 32-32 32H384V312c0-22.1-17.9-40-40-40H232c-22.1 0-40 17.9-40 40V464H144c-17.7 0-32-14.3-32-32zm128 32V320h96V464H240z"/></svg>
        </Link>
        <BackButton></BackButton>
        <Search class="ml-6" />
        <div class="ml-auto">
            <template v-if="user">
                <Dropdown align="right" width="48" ref="userDropdown">
                    <template #trigger>
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white focus:outline-none transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" style="width: 30px; height: 30px;">
                                <path class="fa-secondary" opacity=".4" d="M0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM113 384.2c22.1-38.3 63.5-64.2 111-64.2l64 0c47.4 0 88.9 25.8 111 64.2C363.8 423.3 312.8 448 256 448s-107.8-24.7-143-63.8zM328 200a72 72 0 1 1 -144 0 72 72 0 1 1 144 0z"/><path class="fa-primary" d="M256 272a72 72 0 1 0 0-144 72 72 0 1 0 0 144zm0 176c56.8 0 107.8-24.7 143-63.8C376.9 345.8 335.4 320 288 320l-64 0c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8z"/>
                            </svg>
                            <svg class="ml-2 -mr-0.5 h-4 w-4 transition-transform duration-200"
                                 :class="{ 'rotate-180': userDropdown?.open }"
                                 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </template>
                    <template #content>
                        <DropdownLink :href="route('profile.edit')" class="text-gray-700">
                            Profile
                        </DropdownLink>
                        <DropdownLink :href="route('favorites.index')" class="text-gray-700">
                            My Favorites
                        </DropdownLink>
                        <DropdownLink
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="text-gray-700"
                            :preserve-scroll="true"
                        >
                            Log Out
                        </DropdownLink>
                    </template>
                </Dropdown>
            </template>
            <template v-else>
                <div class="flex gap-4 ml-3">
                    <Link :href="route('login')" class="text-sm text-white whitespace-nowrap">
                        Log in
                    </Link>
                    <Link :href="route('register')" class="text-sm text-white">
                        Register
                    </Link>
                </div>
            </template>
        </div>
    </div>
    <slot />
    <AudioPlayer></AudioPlayer>
</template>
