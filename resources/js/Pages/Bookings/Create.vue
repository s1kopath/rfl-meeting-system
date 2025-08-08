<template>
    <AppLayout>
        <Head title="Book Meeting Room" />
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-2xl font-bold text-gray-900">
                    Book a Meeting Room
                </h2>
                <p class="text-gray-600 mt-2">
                    Select a room, date, and time for your meeting
                </p>
            </div>

            <!-- Booking Form -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Meeting Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="form-label"
                                >Meeting Title</label
                            >
                            <input
                                id="title"
                                v-model="form.title"
                                type="text"
                                required
                                class="form-input"
                                placeholder="Enter meeting title"
                            />
                        </div>

                        <div>
                            <label for="attendees_count" class="form-label"
                                >Number of Attendees</label
                            >
                            <input
                                id="attendees_count"
                                v-model="form.attendees_count"
                                type="number"
                                min="1"
                                required
                                class="form-input"
                            />
                        </div>
                    </div>

                    <div>
                        <label for="description" class="form-label"
                            >Meeting Description</label
                        >
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="3"
                            class="form-input"
                            placeholder="Describe your meeting purpose"
                        ></textarea>
                    </div>

                    <!-- Date and Time Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="date" class="form-label">Date</label>
                            <input
                                id="date"
                                v-model="form.date"
                                type="date"
                                required
                                class="form-input"
                                :min="today"
                            />
                        </div>

                        <div>
                            <label for="start_time" class="form-label"
                                >Start Time</label
                            >
                            <input
                                id="start_time"
                                v-model="form.start_time"
                                type="time"
                                required
                                class="form-input"
                            />
                        </div>

                        <div>
                            <label for="end_time" class="form-label"
                                >End Time</label
                            >
                            <input
                                id="end_time"
                                v-model="form.end_time"
                                type="time"
                                required
                                class="form-input"
                            />
                        </div>
                    </div>

                    <!-- Room Selection -->
                    <div>
                        <label class="form-label">Select Meeting Room</label>
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4"
                        >
                            <div
                                v-for="room in availableRooms"
                                :key="room.id"
                                @click="selectRoom(room)"
                                :class="[
                                    'border-2 rounded-lg p-4 transition-colors duration-200',
                                    selectedRoom?.id === room.id
                                        ? 'border-blue-500 bg-blue-50'
                                        : form.date &&
                                          form.start_time &&
                                          form.end_time &&
                                          !isRoomAvailable(room)
                                        ? 'border-red-300 bg-red-50 cursor-not-allowed opacity-60'
                                        : 'border-gray-200 hover:border-gray-300 cursor-pointer',
                                ]"
                            >
                                <!-- Room Image -->
                                <div
                                    v-if="room.image_url"
                                    class="h-48 overflow-hidden"
                                >
                                    <img
                                        :src="room.image_url"
                                        :alt="room.name"
                                        class="w-full h-full object-cover rounded-lg"
                                    />
                                </div>
                                <div
                                    v-else
                                    class="h-48 bg-gray-200 flex items-center justify-center"
                                >
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
                                        <p class="text-sm text-gray-500">
                                            No image available
                                        </p>
                                    </div>
                                </div>
                                <div
                                    class="flex items-center justify-between mb-2"
                                >
                                    <h3 class="font-semibold text-gray-900">
                                        {{ room.name }}
                                    </h3>
                                    <span class="text-sm text-gray-500"
                                        >{{ room.seats }} seats</span
                                    >
                                </div>
                                <p class="text-sm text-gray-600 mb-3">
                                    {{ room.description }}
                                </p>

                                <!-- Amenities -->
                                <div class="space-y-1">
                                    <div
                                        v-for="amenity in room.amenities_list"
                                        :key="amenity"
                                        class="text-xs text-gray-500"
                                    >
                                        • {{ amenity }}
                                    </div>
                                </div>

                                <!-- Availability Status -->
                                <div class="mt-3">
                                    <div v-if="form.date" class="space-y-2">
                                        <!-- Room Status -->
                                        <!-- <span
                                            :class="[
                                                'px-2 py-1 text-xs font-medium rounded-full',
                                                room.is_active
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-800',
                                            ]"
                                        >
                                            {{
                                                room.is_active
                                                    ? "Active"
                                                    : "Inactive"
                                            }}
                                        </span> -->

                                        <!-- Time Availability -->
                                        <div
                                            v-if="
                                                form.start_time && form.end_time
                                            "
                                        >
                                            <span
                                                :class="[
                                                    'px-2 py-1 text-xs font-medium rounded-full',
                                                    isRoomAvailable(room)
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-red-100 text-red-800',
                                                ]"
                                            >
                                                {{
                                                    isRoomAvailable(room)
                                                        ? "Available for selected time"
                                                        : "Not available for selected time"
                                                }}
                                            </span>
                                        </div>

                                        <!-- Existing Bookings -->
                                        <div
                                            v-if="
                                                getRoomBookings(room).length > 0
                                            "
                                            class="mt-2"
                                        >
                                            <p
                                                class="text-xs text-gray-600 font-medium mb-1"
                                            >
                                                Existing bookings for
                                                {{ form.date }}:
                                            </p>
                                            <div class="space-y-1">
                                                <div
                                                    v-for="booking in getRoomBookings(
                                                        room
                                                    )"
                                                    :key="booking.id"
                                                    class="text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded"
                                                >
                                                    {{
                                                        formatTime(
                                                            booking.start_time
                                                        )
                                                    }}
                                                    -
                                                    {{
                                                        formatTime(
                                                            booking.end_time
                                                        )
                                                    }}
                                                    <span
                                                        class="ml-1 text-gray-400"
                                                    >
                                                        ({{ booking.title }})
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <Link href="/bookings" class="btn-secondary">
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="btn-primary"
                        >
                            <span v-if="form.processing"
                                >Creating Booking...</span
                            >
                            <span v-else>Book Meeting Room</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useForm, Link, Head, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import toast from "@/utils/toast";

const props = defineProps({
    rooms: Array,
    existingBookings: {
        type: Object,
        default: () => ({}),
    },
    selectedDate: String,
    errors: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    title: "",
    description: "",
    date: "",
    start_time: "",
    end_time: "",
    attendees_count: 1,
    meeting_room_id: null,
});

const selectedRoom = ref(null);

const today = computed(() => {
    return new Date().toISOString().split("T")[0];
});

const availableRooms = computed(() => {
    return props.rooms || [];
});

const selectRoom = (room) => {
    // Don't allow selection if room is not available for the selected time
    if (
        form.date &&
        form.start_time &&
        form.end_time &&
        !isRoomAvailable(room)
    ) {
        toast.error("This room is not available for the selected time slot.");
        return;
    }

    selectedRoom.value = room;
    form.meeting_room_id = room.id;
};

const isRoomAvailable = (room) => {

    if (!form.date || !form.start_time || !form.end_time) {
        return room.is_active;
    }

    // Check if room is active
    if (!room.is_active) {
        return false;
    }

    // Get existing bookings for this room on the selected date
    const roomBookings = props.existingBookings[room.id] || [];

    // Check for time conflicts
    for (const booking of roomBookings) {
        const bookingStart = booking.start_time;
        const bookingEnd = booking.end_time;
        const selectedStart = form.start_time;
        const selectedEnd = form.end_time;

        // Check if there's any overlap
        if (selectedStart < bookingEnd && selectedEnd > bookingStart) {
            return false;
        }
    }

    return true;
};

const getRoomBookings = (room) => {
    return props.existingBookings[room.id] || [];
};

const formatTime = (time) => {
    return new Date(`2000-01-01T${time}`).toLocaleTimeString("en-US", {
        hour: "numeric",
        minute: "2-digit",
        hour12: true,
    });
};

const submit = () => {
    // Validate that a room is selected
    if (!selectedRoom.value) {
        toast.error("Please select a meeting room.");
        return;
    }

    form.post("/bookings", {
        onSuccess: () => {
            toast.success(
                "Booking created successfully! Waiting for approval."
            );
            router.visit("/bookings");
        },
        onError: (errors) => {
            toast.showValidationErrors(errors);
        },
    });
};

// Watch for date changes to reload booking data
watch(
    () => form.date,
    (newDate) => {
        if (newDate) {
            router.get(
                "/bookings/create",
                { date: newDate },
                {
                    preserveState: true,
                    preserveScroll: true,
                }
            );
        }
    }
);

// Show validation errors on mount if any
onMounted(() => {
    if (Object.keys(props.errors).length > 0) {
        toast.showValidationErrors(props.errors);
    }

    // Set initial date if provided
    if (props.selectedDate) {
        form.date = props.selectedDate;
    }
});
</script>
