<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthLayout from "@/Layouts/AuthLayout.vue";
defineOptions({ layout: AuthLayout });

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <Head title="Email Verification" />

    <div class="w-full h-full flex justify-center items-center">
        <div class="w-96 p-6 rounded-2xl shadow-xl border-1 border-gray-800" style="background: rgb(31,41,54); background: linear-gradient(170deg, #121b2c 55%, rgb(0 0 0) 100%)">
            <h1 class="mb-4">Verify Email</h1>
            
            <div class="mb-4 text-sm text-gray-300">
                Thanks for signing up! Before getting started, could you verify your
                email address by clicking on the link we just emailed to you? If you
                didn't receive the email, we will gladly send you another.
            </div>

            <div
                class="mb-4 text-sm font-medium text-green-400"
                v-if="verificationLinkSent"
            >
                A new verification link has been sent to the email address you
                provided during registration.
            </div>

            <form @submit.prevent="submit">
                <div class="mt-6 flex items-center justify-between">
                    <button
                        type="submit"
                        class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800"
                        :disabled="form.processing"
                    >
                        Resend Verification Email
                    </button>

                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="text-sm text-gray-300 hover:text-white focus:outline-none"
                    >
                        Log Out
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>
