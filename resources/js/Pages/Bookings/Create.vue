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
                                    'border-2 rounded-lg p-4 cursor-pointer transition-colors duration-200',
                                    selectedRoom?.id === room.id
                                        ? 'border-blue-500 bg-blue-50'
                                        : 'border-gray-200 hover:border-gray-300',
                                ]"
                            >
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
                                                ? "Available"
                                                : "Unavailable"
                                        }}
                                    </span> -->
                                    <div
                                        v-if="
                                            form.date &&
                                            form.start_time &&
                                            form.end_time
                                        "
                                        class="mt-1"
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
import { ref, computed, onMounted } from "vue";
import { useForm, Link, Head, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import toast from "@/utils/toast";

const props = defineProps({
    rooms: Array,
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
    selectedRoom.value = room;
    form.meeting_room_id = room.id;
};

const isRoomAvailable = (room) => {
    if (!form.date || !form.start_time || !form.end_time) {
        return room.is_active;
    }

    // Simple availability check - in a real app, you'd check against existing bookings
    return room.is_active;
};

const submit = () => {
    // Debug: Log the form data
    console.log("Form data:", form.data());
    console.log("Selected room:", selectedRoom.value);

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
            console.log("Form errors:", errors);
            toast.showValidationErrors(errors);
        },
    });
};

// Show validation errors on mount if any
onMounted(() => {
    if (Object.keys(props.errors).length > 0) {
        toast.showValidationErrors(props.errors);
    }
});
</script>
