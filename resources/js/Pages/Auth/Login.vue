<script setup>
import {Head, useForm} from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";
defineOptions({ layout: AuthLayout });
let form = useForm({
    email: null,
    password: null,
    remember: false
});
</script>
<template>
    <Head title="Log In"></Head>

    <div class="w-full h-full flex justify-center items-center">
        <div class="w-96 p-6 rounded-2xl shadow-xl border-1 border-gray-800" style="background: rgb(31,41,54); background: linear-gradient(170deg, #121b2c 55%, rgb(0 0 0) 100%)">
            <h1>Login</h1>
            <form @submit.prevent="form.post('/login')">
                <div class="mb-5">
                    <label for="email" class="block mb-2 text-sm font-medium text-white">Your email</label>
                    <input type="text"
                           id="email"
                           class="border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500"
                           required
                           v-model="form.email"
                    />
                </div>
                <div class="mb-5">
                    <label for="password" class="block mb-2 text-sm font-medium text-white">Your password</label>
                    <input type="password"
                           id="password"
                           class="border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500"
                           required
                           v-model="form.password"
                    />
                </div>
                <div class="flex items-start mb-5">
                    <div class="flex items-center h-5">
                        <input id="remember"
                               type="checkbox"
                               class="w-4 h-4 border rounded focus:ring-3 bg-gray-700 border-gray-600 focus:ring-blue-600 ring-offset-gray-800 focus:ring-offset-gray-800"
                               v-model="form.remember"
                        />
                    </div>
                    <label for="remember" class="ms-2 text-sm font-medium text-gray-300">Remember me</label>
                </div>
                <button
                    type="submit"
                    class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800"
                    :disabled="form.processing"
                >Submit</button>
            </form>
        </div>
    </div>
</template>
