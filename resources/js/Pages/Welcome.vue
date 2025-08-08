<template>
    <div class="min-h-screen bg-gray-50">
        <Head title="Welcome" />
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="container mx-auto px-4 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            RFL Meeting System
                        </h1>
                        <p class="text-gray-600 mt-2">
                            Efficient meeting room booking and management
                        </p>
                    </div>
                    <div class="flex space-x-4">
                        <!-- Show panel button if user is logged in -->
                        <Link
                            v-if="auth.user"
                            :href="getPanelUrl()"
                            class="btn-primary"
                        >
                            Go to Panel
                        </Link>
                        <!-- Show login/register if user is not logged in -->
                        <Link
                            v-else-if="canLogin"
                            href="/login"
                            class="btn-primary"
                        >
                            Log in
                        </Link>
                        <Link
                            v-if="!auth.user && canRegister"
                            href="/register"
                            class="btn-secondary"
                        >
                            Register
                        </Link>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-12">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Streamline Your Meeting Room Management
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Book meeting rooms, manage schedules, and coordinate team
                    meetings with ease. Our mobile-first platform makes room
                    booking simple and efficient.
                </p>
            </div>

            <!-- Features -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="text-center">
                    <div
                        class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4"
                    >
                        <svg
                            class="w-8 h-8 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                            ></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        Easy Booking
                    </h3>
                    <p class="text-gray-600">
                        Book meeting rooms in seconds with our intuitive
                        interface
                    </p>
                </div>

                <div class="text-center">
                    <div
                        class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4"
                    >
                        <svg
                            class="w-8 h-8 text-green-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                            ></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        Real-time Availability
                    </h3>
                    <p class="text-gray-600">
                        See room availability in real-time and avoid conflicts
                    </p>
                </div>

                <div class="text-center">
                    <div
                        class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4"
                    >
                        <svg
                            class="w-8 h-8 text-purple-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                            ></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        Mobile First
                    </h3>
                    <p class="text-gray-600">
                        Optimized for mobile devices for booking on the go
                    </p>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    Ready to get started?
                </h3>
                <p class="text-gray-600 mb-6">
                    Join thousands of users who trust our platform for their
                    meeting room needs.
                </p>
                <div class="flex justify-center space-x-4">
                    <!-- Show panel button if user is logged in -->
                    <Link
                        v-if="auth.user"
                        :href="getPanelUrl()"
                        class="btn-primary"
                    >
                        Go to Panel
                    </Link>
                    <!-- Show register/login if user is not logged in -->
                    <Link
                        v-else-if="canRegister"
                        href="/register"
                        class="btn-primary"
                    >
                        Get Started
                    </Link>
                    <Link
                        v-if="!auth.user && canLogin"
                        href="/login"
                        class="btn-secondary"
                    >
                        Sign In
                    </Link>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="container mx-auto px-4 py-8">
                <div class="text-center text-sm text-gray-600">
                    <p>
                        &copy; {{ new Date().getFullYear() }} RFL Meeting
                        System. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    auth: Object,
});

const getPanelUrl = () => {
    if (!props.auth.user) return "/dashboard";

    // Route users to appropriate panel based on their role
    switch (props.auth.user.role) {
        case "admin":
            return "/admin/rooms";
        case "staff":
            return "/staff/rooms";
        default:
            return "/dashboard";
    }
};
</script>
