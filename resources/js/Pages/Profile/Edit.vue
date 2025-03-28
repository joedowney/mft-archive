<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import Page from "@/Components/Page.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />
    <Page>
    <div class="max-w-4xl w-full mx-auto px-4 lg:px-0 mt-8">
        <h1 class="text-2xl font-bold mb-6">Your Profile</h1>

        <div class="space-y-6">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                />
            </div>

            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <UpdatePasswordForm />
            </div>

            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <DeleteUserForm />
            </div>
        </div>
    </div>
    </Page>
</template>
