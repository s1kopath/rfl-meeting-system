<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <!-- Logo and Brand -->
                    <div class="flex items-center">
                        <Link
                            href="/dashboard"
                            class="flex items-center space-x-3"
                        >
                            <img
                                src="/rfl_0.png"
                                alt="RFL Logo"
                                class="h-10 w-10"
                            />
                            <span class="text-xl font-bold text-gray-900">
                                RFL Meeting System
                            </span>
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <nav class="hidden md:flex space-x-8">
                        <Link
                            href="/dashboard"
                            :class="[
                                'text-sm font-medium transition-colors duration-200',
                                $page.url === '/dashboard'
                                    ? 'text-red-600'
                                    : 'text-gray-500 hover:text-gray-700',
                            ]"
                        >
                            Dashboard
                        </Link>

                        <Link
                            href="/rooms"
                            :class="[
                                'text-sm font-medium transition-colors duration-200',
                                $page.url.startsWith('/rooms')
                                    ? 'text-red-600'
                                    : 'text-gray-500 hover:text-gray-700',
                            ]"
                        >
                            Meeting Rooms
                        </Link>

                        <Link
                            href="/bookings"
                            :class="[
                                'text-sm font-medium transition-colors duration-200',
                                $page.url.startsWith('/bookings')
                                    ? 'text-red-600'
                                    : 'text-gray-500 hover:text-gray-700',
                            ]"
                        >
                            My Bookings
                        </Link>

                        <!-- Admin Navigation -->
                        <template
                            v-if="
                                $page.props.auth.user &&
                                $page.props.auth.user.role === 'admin'
                            "
                        >
                            <Link
                                href="/admin/users"
                                :class="[
                                    'text-sm font-medium transition-colors duration-200',
                                    $page.url.startsWith('/admin/users')
                                        ? 'text-red-600'
                                        : 'text-gray-500 hover:text-gray-700',
                                ]"
                            >
                                Users
                            </Link>

                            <Link
                                href="/admin/rooms"
                                :class="[
                                    'text-sm font-medium transition-colors duration-200',
                                    $page.url.startsWith('/admin/rooms')
                                        ? 'text-red-600'
                                        : 'text-gray-500 hover:text-gray-700',
                                ]"
                            >
                                Manage Rooms
                            </Link>

                            <Link
                                href="/admin/bookings"
                                :class="[
                                    'text-sm font-medium transition-colors duration-200',
                                    $page.url.startsWith('/admin/bookings')
                                        ? 'text-red-600'
                                        : 'text-gray-500 hover:text-gray-700',
                                ]"
                            >
                                All Bookings
                            </Link>
                        </template>

                        <!-- Staff Navigation -->
                        <template
                            v-if="
                                $page.props.auth.user &&
                                $page.props.auth.user.role === 'staff'
                            "
                        >
                            <Link
                                href="/staff/rooms"
                                :class="[
                                    'text-sm font-medium transition-colors duration-200',
                                    $page.url.startsWith('/staff/rooms')
                                        ? 'text-red-600'
                                        : 'text-gray-500 hover:text-gray-700',
                                ]"
                            >
                                Manage Rooms
                            </Link>

                            <Link
                                href="/staff/bookings"
                                :class="[
                                    'text-sm font-medium transition-colors duration-200',
                                    $page.url.startsWith('/staff/bookings')
                                        ? 'text-red-600'
                                        : 'text-gray-500 hover:text-gray-700',
                                ]"
                            >
                                Approve Bookings
                            </Link>
                        </template>
                    </nav>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button
                                @click="userMenuOpen = !userMenuOpen"
                                class="flex items-center space-x-2 text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none"
                            >
                                <div
                                    class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center"
                                >
                                    <span
                                        class="text-sm font-medium text-red-700"
                                    >
                                        {{
                                            $page.props.auth.user?.name
                                                ?.charAt(0)
                                                .toUpperCase()
                                        }}
                                    </span>
                                </div>
                                <span class="hidden md:block">{{
                                    $page.props.auth.user?.name
                                }}</span>
                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    ></path>
                                </svg>
                            </button>

                            <!-- User Dropdown Menu -->
                            <div
                                v-if="userMenuOpen"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                            >
                                <div
                                    class="px-4 py-2 text-sm text-gray-700 border-b"
                                >
                                    <div class="font-medium">
                                        {{ $page.props.auth.user?.name }}
                                    </div>
                                    <div class="text-gray-500">
                                        {{ $page.props.auth.user?.email }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        Role: {{ $page.props.auth.user?.role }}
                                    </div>
                                </div>

                                <Link
                                    href="/profile"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    @click="userMenuOpen = false"
                                >
                                    Profile
                                </Link>

                                <form @submit.prevent="logout" class="block">
                                    <button
                                        type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    >
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div class="md:hidden">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <Link
                        href="/dashboard"
                        :class="[
                            'block px-3 py-2 rounded-md text-base font-medium',
                            $page.url === '/dashboard'
                                ? 'bg-red-100 text-red-700'
                                : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50',
                        ]"
                    >
                        Dashboard
                    </Link>

                    <Link
                        href="/rooms"
                        :class="[
                            'block px-3 py-2 rounded-md text-base font-medium',
                            $page.url.startsWith('/rooms')
                                ? 'bg-red-100 text-red-700'
                                : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50',
                        ]"
                    >
                        Meeting Rooms
                    </Link>

                    <Link
                        href="/bookings"
                        :class="[
                            'block px-3 py-2 rounded-md text-base font-medium',
                            $page.url.startsWith('/bookings')
                                ? 'bg-red-100 text-red-700'
                                : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50',
                        ]"
                    >
                        My Bookings
                    </Link>

                    <!-- Admin Mobile Navigation -->
                    <template
                        v-if="
                            $page.props.auth.user &&
                            $page.props.auth.user.role === 'admin'
                        "
                    >
                        <Link
                            href="/admin/users"
                            :class="[
                                'block px-3 py-2 rounded-md text-base font-medium',
                                $page.url.startsWith('/admin/users')
                                    ? 'bg-red-100 text-red-700'
                                    : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50',
                            ]"
                        >
                            Users
                        </Link>

                        <Link
                            href="/admin/rooms"
                            :class="[
                                'block px-3 py-2 rounded-md text-base font-medium',
                                $page.url.startsWith('/admin/rooms')
                                    ? 'bg-red-100 text-red-700'
                                    : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50',
                            ]"
                        >
                            Manage Rooms
                        </Link>

                        <Link
                            href="/admin/bookings"
                            :class="[
                                'block px-3 py-2 rounded-md text-base font-medium',
                                $page.url.startsWith('/admin/bookings')
                                    ? 'bg-red-100 text-red-700'
                                    : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50',
                            ]"
                        >
                            All Bookings
                        </Link>
                    </template>

                    <!-- Staff Mobile Navigation -->
                    <template
                        v-if="
                            $page.props.auth.user &&
                            $page.props.auth.user.role === 'staff'
                        "
                    >
                        <Link
                            href="/staff/rooms"
                            :class="[
                                'block px-3 py-2 rounded-md text-base font-medium',
                                $page.url.startsWith('/staff/rooms')
                                    ? 'bg-red-100 text-red-700'
                                    : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50',
                            ]"
                        >
                            Manage Rooms
                        </Link>

                        <Link
                            href="/staff/bookings"
                            :class="[
                                'block px-3 py-2 rounded-md text-base font-medium',
                                $page.url.startsWith('/staff/bookings')
                                    ? 'bg-red-100 text-red-700'
                                    : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50',
                            ]"
                        >
                            Approve Bookings
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-sm text-gray-500">
                    <p>
                        &copy; 2024 RFL Meeting Room Booking System. All rights
                        reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Link, useForm } from "@inertiajs/vue3";

const userMenuOpen = ref(false);

const logout = () => {
    useForm().post("/logout");
};
</script>
