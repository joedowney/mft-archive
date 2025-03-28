<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthLayout from "@/Layouts/AuthLayout.vue";
defineOptions({ layout: AuthLayout });

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

    <div class="w-full h-full flex justify-center items-center">
        <div class="w-96 p-6 rounded-2xl shadow-xl border-1 border-gray-800" style="background: rgb(31,41,54); background: linear-gradient(170deg, #121b2c 55%, rgb(0 0 0) 100%)">
            <h1>Register</h1>
            <form @submit.prevent="submit">
                <div class="mb-5">
                    <label for="name" class="block mb-2 text-sm font-medium text-white">Your name</label>
                    <input 
                        type="text"
                        id="name"
                        class="border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500"
                        required
                        autofocus
                        v-model="form.name"
                        autocomplete="name"
                    />
                    <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                </div>

                <div class="mb-5">
                    <label for="email" class="block mb-2 text-sm font-medium text-white">Your email</label>
                    <input 
                        type="email"
                        id="email"
                        class="border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500"
                        required
                        v-model="form.email"
                        autocomplete="username"
                    />
                    <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
                </div>

                <div class="mb-5">
                    <label for="password" class="block mb-2 text-sm font-medium text-white">Your password</label>
                    <input 
                        type="password"
                        id="password"
                        class="border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500"
                        required
                        v-model="form.password"
                        autocomplete="new-password"
                    />
                    <div v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</div>
                </div>

                <div class="mb-5">
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-white">Confirm password</label>
                    <input 
                        type="password"
                        id="password_confirmation"
                        class="border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500"
                        required
                        v-model="form.password_confirmation"
                        autocomplete="new-password"
                    />
                    <div v-if="form.errors.password_confirmation" class="text-red-500 text-xs mt-1">{{ form.errors.password_confirmation }}</div>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <Link
                        :href="route('login')"
                        class="text-sm text-gray-300 hover:text-white focus:outline-none"
                    >
                        Already registered?
                    </Link>

                    <button
                        type="submit"
                        class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800"
                        :disabled="form.processing"
                    >
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
