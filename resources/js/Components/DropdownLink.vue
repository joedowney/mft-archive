<script setup>
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    method: {
        type: String,
        default: 'get',
    },
    as: {
        type: String,
        default: 'a',
    },
    preserveScroll: {
        type: Boolean,
        default: false
    }
});

const visit = () => {
    if (props.method.toLowerCase() === 'post') {
        router.post(props.href, {}, {
            preserveScroll: props.preserveScroll,
            onSuccess: () => {
                if (props.href === route('logout')) {
                    window.location.href = '/';
                }
            },
        });
    }
};
</script>

<template>
    <div v-if="method.toLowerCase() === 'post' && as === 'button'">
        <button
            type="button"
            @click="visit"
            class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
        >
            <slot />
        </button>
    </div>
    <Link
        v-else
        :href="href"
        :method="method"
        :as="as"
        :preserve-scroll="preserveScroll"
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
    >
        <slot />
    </Link>
</template>
