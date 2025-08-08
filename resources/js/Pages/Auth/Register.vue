<template>
    <div
        class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8"
    >
        <Head title="Register" />
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2
                class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900"
            >
                Create your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Or
                <Link
                    href="/login"
                    class="font-medium text-blue-600 hover:text-blue-500"
                >
                    sign in to your existing account
                </Link>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label for="name" class="form-label">Full name</label>
                        <div class="mt-1">
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                class="form-input"
                                :class="{
                                    'border-red-500': errors && errors.name,
                                }"
                            />
                        </div>
                        <p
                            v-if="errors && errors.name"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ errors.name }}
                        </p>
                    </div>

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
                                :class="{
                                    'border-red-500': errors && errors.email,
                                }"
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
                                :class="{
                                    'border-red-500': errors && errors.password,
                                }"
                            />
                        </div>
                        <p
                            v-if="errors && errors.password"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ errors.password }}
                        </p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label"
                            >Confirm password</label
                        >
                        <div class="mt-1">
                            <input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                required
                                class="form-input"
                                :class="{
                                    'border-red-500':
                                        errors && errors.password_confirmation,
                                }"
                            />
                        </div>
                        <p
                            v-if="errors && errors.password_confirmation"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ errors.password_confirmation }}
                        </p>
                    </div>

                    <div>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full btn-primary"
                        >
                            <span v-if="form.processing"
                                >Creating account...</span
                            >
                            <span v-else>Create account</span>
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
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post("/register");
};

defineProps({
    errors: {
        type: Object,
        default: () => ({}),
    },
});
</script>
