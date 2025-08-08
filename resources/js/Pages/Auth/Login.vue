<template>
    <div
        class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8"
    >
        <Head title="Login" />
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2
                class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900"
            >
                Sign in to your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Or
                <Link
                    href="/register"
                    class="font-medium text-blue-600 hover:text-blue-500"
                >
                    create a new account
                </Link>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label for="email" class="form-label"
                            >Email address</label
                        >
                        <div class="mt-1">
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                class="form-input"
                                :class="{ 'border-red-500': errors && errors.email }"
                            />
                        </div>
                        <p
                            v-if="errors && errors.email"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ errors.email }}
                        </p>
                    </div>

                    <div>
                        <label for="password" class="form-label"
                            >Password</label
                        >
                        <div class="mt-1">
                            <input
                                id="password"
                                v-model="form.password"
                                type="password"
                                required
                                class="form-input"
                                :class="{ 'border-red-500': errors && errors.password }"
                            />
                        </div>
                        <p
                            v-if="errors && errors.password"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ errors.password }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input
                                id="remember"
                                v-model="form.remember"
                                type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            />
                            <label
                                for="remember"
                                class="ml-2 block text-sm text-gray-900"
                            >
                                Remember me
                            </label>
                        </div>
                    </div>

                    <div>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full btn-primary"
                        >
                            <span v-if="form.processing">Signing in...</span>
                            <span v-else>Sign in</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link, Head } from "@inertiajs/vue3";

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post("/login");
};

defineProps({
    errors: {
        type: Object,
        default: () => ({})
    },
});
</script>
