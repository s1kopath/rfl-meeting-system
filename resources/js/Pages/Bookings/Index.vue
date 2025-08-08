<template>
    <AppLayout>
        <Head title="My Bookings" />
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            My Bookings
                        </h2>
                        <p class="text-gray-600 mt-2">
                            Manage your meeting room bookings
                        </p>
                    </div>
                    <Link href="/bookings/create" class="btn-primary">
                        Book New Meeting
                    </Link>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="status_filter" class="form-label"
                            >Status</label
                        >
                        <select
                            id="status_filter"
                            v-model="filters.status"
                            class="form-input"
                            @change="applyFilters"
                        >
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="rejected">Rejected</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label for="date_filter" class="form-label">Date</label>
                        <input
                            id="date_filter"
                            v-model="filters.date"
                            type="date"
                            class="form-input"
                            @change="applyFilters"
                        />
                    </div>

                    <div>
                        <label for="room_filter" class="form-label">Room</label>
                        <select
                            id="room_filter"
                            v-model="filters.room"
                            class="form-input"
                            @change="applyFilters"
                        >
                            <option value="">All Rooms</option>
                            <option
                                v-for="room in rooms"
                                :key="room.id"
                                :value="room.id"
                            >
                                {{ room.name }}
                            </option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button
                            @click="clearFilters"
                            class="btn-secondary w-full"
                        >
                            Clear Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bookings List -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Upcoming Bookings
                    </h3>
                </div>

                <div v-if="bookings.length === 0" class="p-6 text-center">
                    <p class="text-gray-500">No bookings found.</p>
                </div>

                <div v-else class="divide-y divide-gray-200">
                    <div
                        v-for="booking in bookings"
                        :key="booking.id"
                        class="p-6 hover:bg-gray-50 transition-colors duration-200"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <h4
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        {{ booking.title }}
                                    </h4>
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
                                                'bg-gray-100 text-gray-800':
                                                    booking.status ===
                                                    'cancelled',
                                            },
                                        ]"
                                    >
                                        {{ booking.status }}
                                    </span>
                                </div>

                                <div
                                    class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600"
                                >
                                    <div>
                                        <span class="font-medium">Room:</span>
                                        {{ booking.meeting_room?.name }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Date:</span>
                                        {{ booking.date }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Time:</span>
                                        {{ booking.formatted_time }}
                                    </div>
                                </div>

                                <div class="mt-2 text-sm text-gray-600">
                                    <span class="font-medium">Attendees:</span>
                                    {{ booking.attendees_count }}
                                </div>

                                <div
                                    v-if="booking.description"
                                    class="mt-2 text-sm text-gray-600"
                                >
                                    <span class="font-medium"
                                        >Description:</span
                                    >
                                    {{ booking.description }}
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                <Link
                                    :href="`/bookings/${booking.id}`"
                                    class="btn-secondary text-sm"
                                >
                                    View
                                </Link>

                                <Link
                                    v-if="booking.can_be_edited"
                                    :href="`/bookings/${booking.id}/edit`"
                                    class="btn-secondary text-sm"
                                >
                                    Edit
                                </Link>

                                <button
                                    v-if="booking.can_be_cancelled"
                                    @click="cancelBooking(booking)"
                                    class="btn-danger text-sm"
                                >
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="bookings.length > 0"
                class="bg-white rounded-lg shadow-sm p-6"
            >
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing {{ pagination.from }} to {{ pagination.to }} of
                        {{ pagination.total }} results
                    </div>
                    <div class="flex space-x-2">
                        <button
                            v-if="pagination.prev_page_url"
                            @click="loadPage(pagination.current_page - 1)"
                            class="btn-secondary text-sm"
                        >
                            Previous
                        </button>
                        <button
                            v-if="pagination.next_page_url"
                            @click="loadPage(pagination.current_page + 1)"
                            class="btn-secondary text-sm"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import { useForm, Link, Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    bookings: Array,
    rooms: Array,
    pagination: Object,
    filters: Object,
});

const filters = ref({
    status: props.filters?.status || "",
    date: props.filters?.date || "",
    room: props.filters?.room || "",
});

const applyFilters = () => {
    // This would trigger a new request with filters
    alert("Under development");
    console.log("Applying filters:", filters.value);
};

const clearFilters = () => {
    filters.value = {
        status: "",
        date: "",
        room: "",
    };
    applyFilters();
};

const loadPage = (page) => {
    // This would load a specific page
    alert("Under development");
    console.log("Loading page:", page);
};

const cancelBooking = (booking) => {
    if (confirm("Are you sure you want to cancel this booking?")) {
        // This would cancel the booking
        alert("Under development");
        console.log("Cancelling booking:", booking.id);
    }
};
</script>
