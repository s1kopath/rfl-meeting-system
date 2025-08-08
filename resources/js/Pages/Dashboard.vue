<template>
    <AppLayout>
        <Head title="Dashboard" />
        <div class="space-y-6">
            <!-- Header -->
            <div class="modern-card">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            Dashboard
                        </h2>
                        <p class="text-gray-600 mt-2">
                            Welcome back, {{ $page.props.auth.user?.name }}!
                        </p>
                    </div>
                    <button @click="testToast" class="modern-btn-primary">
                        Test Toast
                    </button>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="modern-card">
                    <div class="flex items-center">
                        <div class="p-2 bg-red-100 rounded-lg">
                            <svg
                                class="w-6 h-6 text-red-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                ></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">
                                Total Rooms
                            </p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ stats.totalRooms }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="modern-card">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg
                                class="w-6 h-6 text-green-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">
                                My Bookings
                            </p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ stats.myBookings }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="modern-card">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <svg
                                class="w-6 h-6 text-yellow-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">
                                Pending
                            </p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ stats.pendingBookings }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="modern-card">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg
                                class="w-6 h-6 text-purple-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                ></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">
                                This Month
                            </p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ stats.monthlyBookings }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="modern-card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Quick Actions
                    </h3>
                    <div class="space-y-3">
                        <Link
                            href="/rooms"
                            class="w-full modern-btn-primary text-center block"
                        >
                            Browse Meeting Rooms
                        </Link>
                        <Link
                            href="/bookings/create"
                            class="w-full modern-btn-secondary text-center block"
                        >
                            Book a Room
                        </Link>
                        <Link
                            href="/bookings"
                            class="w-full modern-btn-secondary text-center block"
                        >
                            View My Bookings
                        </Link>
                    </div>
                </div>

                <div class="modern-card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Recent Bookings
                    </h3>
                    <div
                        v-if="recentBookings.length === 0"
                        class="text-center py-4"
                    >
                        <p class="text-gray-500">No recent bookings</p>
                    </div>
                    <div v-else class="space-y-3">
                        <div
                            v-for="booking in recentBookings"
                            :key="booking.id"
                            class="p-3 bg-gray-50 rounded-lg"
                        >
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-medium text-gray-900">
                                        {{ booking.title }}
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        {{ booking.date }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ booking.formatted_time }}
                                    </p>
                                </div>
                                <span
                                    :class="[
                                        'status-badge',
                                        {
                                            'status-confirmed':
                                                booking.status === 'confirmed',
                                            'status-pending':
                                                booking.status === 'pending',
                                            'status-rejected':
                                                booking.status === 'rejected',
                                        },
                                    ]"
                                >
                                    {{ booking.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modern-card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Available Rooms
                    </h3>
                    <div
                        v-if="availableRooms.length === 0"
                        class="text-center py-4"
                    >
                        <p class="text-gray-500">No rooms available</p>
                    </div>
                    <div v-else class="space-y-3">
                        <div
                            v-for="room in availableRooms"
                            :key="room.id"
                            class="p-3 bg-gray-50 rounded-lg"
                        >
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-medium text-gray-900">
                                        {{ room.name }}
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        {{ room.seats }} seats
                                    </p>
                                </div>
                                <Link
                                    :href="`/rooms/${room.id}`"
                                    class="text-red-600 hover:text-red-800 text-sm"
                                >
                                    View
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import toast from "@/utils/toast";

const props = defineProps({
    stats: Object,
    recentBookings: Array,
    availableRooms: Array,
});

const testToast = () => {
    toast.success("This is a test toast notification! 🎉");
};
</script>
