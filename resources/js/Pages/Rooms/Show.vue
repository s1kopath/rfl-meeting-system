<template>
    <AppLayout>
        <Head :title="`${room.name} - Room Details`" />
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            {{ room.name }}
                        </h2>
                        <p class="text-gray-600 mt-2">{{ room.description }}</p>
                    </div>
                    <Link href="/rooms" class="btn-secondary">
                        Back to Rooms
                    </Link>
                </div>
            </div>

            <!-- Room Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Room Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Room Image -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div v-if="room.image_url" class="h-64 rounded-lg overflow-hidden">
                            <img
                                :src="room.image_url"
                                :alt="room.name"
                                class="w-full h-full object-cover"
                            />
                        </div>
                        <div v-else class="h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <svg
                                    class="w-16 h-16 text-gray-400 mx-auto mb-2"
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
                                <p class="text-sm text-gray-500">No image available</p>
                            </div>
                        </div>
                    </div>

                    <!-- Room Specifications -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Room Specifications
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-100"
                            >
                                <span class="text-gray-600">Capacity:</span>
                                <span class="font-medium"
                                    >{{ room.seats }} seats</span
                                >
                            </div>
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-100"
                            >
                                <span class="text-gray-600">Tables:</span>
                                <span class="font-medium">{{
                                    room.tables
                                }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-100"
                            >
                                <span class="text-gray-600">AC Units:</span>
                                <span class="font-medium">{{
                                    room.ac_units
                                }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-100"
                            >
                                <span class="text-gray-600"
                                    >Washroom Facilities:</span
                                >
                                <span class="font-medium">{{
                                    room.washroom_facilities
                                }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-100"
                            >
                                <span class="text-gray-600">Monitors:</span>
                                <span class="font-medium">{{
                                    room.monitors
                                }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-100"
                            >
                                <span class="text-gray-600">TV Screens:</span>
                                <span class="font-medium">{{
                                    room.tv_screens
                                }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Amenities -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Amenities
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div
                                v-for="amenity in room.amenities_list"
                                :key="amenity"
                                class="flex items-center"
                            >
                                <svg
                                    class="w-5 h-5 text-green-500 mr-3"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    ></path>
                                </svg>
                                <span class="text-gray-700">{{ amenity }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Quick Actions
                        </h3>
                        <div class="space-y-3 space-x-2">
                            <Link
                                :href="`/bookings/create?room=${room.id}`"
                                class="w-full btn-primary text-center"
                            >
                                Book This Room
                            </Link>
                            <Link
                                href="/rooms"
                                class="w-full btn-secondary text-center"
                            >
                                View All Rooms
                            </Link>
                        </div>
                    </div>

                    <!-- Room Status -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Room Status
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Availability:</span>
                                <span
                                    :class="[
                                        'px-2 py-1 text-xs font-medium rounded-full',
                                        room.is_active
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800',
                                    ]"
                                >
                                    {{
                                        room.is_active
                                            ? "Available"
                                            : "Unavailable"
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Bookings -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Upcoming Bookings
                        </h3>
                        <div
                            v-if="room.bookings && room.bookings.length > 0"
                            class="space-y-3"
                        >
                            <div
                                v-for="booking in room.bookings"
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
                                            'px-2 py-1 text-xs font-medium rounded-full',
                                            {
                                                'bg-green-100 text-green-800':
                                                    booking.status ===
                                                    'confirmed',
                                                'bg-yellow-100 text-yellow-800':
                                                    booking.status ===
                                                    'pending',
                                                'bg-red-100 text-red-800':
                                                    booking.status ===
                                                    'rejected',
                                            },
                                        ]"
                                    >
                                        {{ booking.status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-4">
                            <p class="text-gray-500">No upcoming bookings</p>
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

const props = defineProps({
    room: Object,
});
</script>
