<template>
    <AppLayout>
        <Head title="Room Management" />
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            Meeting Room Management
                        </h2>
                        <p class="text-gray-600 mt-2">
                            Manage meeting rooms and their amenities
                        </p>
                    </div>
                    <button @click="showCreateModal = true" class="btn-primary">
                        Add New Room
                    </button>
                </div>
            </div>

            <!-- Room Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg
                                class="w-6 h-6 text-blue-600"
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

                <div class="bg-white rounded-lg shadow-sm p-6">
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
                                Available
                            </p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ stats.availableRooms }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
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
                                Booked Today
                            </p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ stats.bookedToday }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
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
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                ></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">
                                Total Capacity
                            </p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ stats.totalCapacity }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rooms List -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Meeting Rooms
                    </h3>
                </div>

                <div v-if="rooms.length === 0" class="p-6 text-center">
                    <p class="text-gray-500">No meeting rooms found.</p>
                </div>

                <div
                    v-else
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6"
                >
                    <div
                        v-for="room in rooms"
                        :key="room.id"
                        class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200"
                    >
                        <!-- Room Image -->
                        <div class="mb-4">
                            <div
                                class="relative h-48 bg-gray-100 rounded-lg overflow-hidden"
                            >
                                <img
                                    v-if="room.image_url"
                                    :src="room.image_url"
                                    :alt="room.name"
                                    class="w-full h-full object-cover"
                                />
                                <div
                                    v-else
                                    class="w-full h-full flex items-center justify-center"
                                >
                                    <svg
                                        class="w-12 h-12 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-900">
                                {{ room.name }}
                            </h4>
                            <span
                                :class="[
                                    'px-2 py-1 text-xs font-medium rounded-full',
                                    room.is_active
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-red-100 text-red-800',
                                ]"
                            >
                                {{ room.is_active ? "Active" : "Inactive" }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 mb-4">
                            {{ room.description }}
                        </p>

                        <!-- Room Details -->
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Capacity:</span>
                                <span class="font-medium"
                                    >{{ room.seats }} seats</span
                                >
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tables:</span>
                                <span class="font-medium">{{
                                    room.tables
                                }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">AC Units:</span>
                                <span class="font-medium">{{
                                    room.ac_units
                                }}</span>
                            </div>
                        </div>

                        <!-- Amenities -->
                        <div class="mb-4">
                            <h5 class="text-sm font-medium text-gray-900 mb-2">
                                Amenities:
                            </h5>
                            <div class="flex flex-wrap gap-1">
                                <span
                                    v-for="amenity in room.amenities_list"
                                    :key="amenity"
                                    class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded"
                                >
                                    {{ amenity }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2">
                            <button
                                @click="editRoom(room)"
                                class="btn-secondary text-sm flex-1"
                            >
                                Edit
                            </button>
                            <button
                                @click="toggleRoomStatus(room)"
                                :class="[
                                    'text-sm flex-1',
                                    room.is_active
                                        ? 'btn-warning'
                                        : 'btn-info',
                                ]"
                            >
                                {{ room.is_active ? "Deactivate" : "Activate" }}
                            </button>
                            <button
                                @click="deleteRoom(room)"
                                class="btn-danger text-sm flex-1"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create Room Modal -->
            <div
                v-if="showCreateModal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            >
                <div
                    class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white"
                >
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Add New Meeting Room
                        </h3>
                        <form @submit.prevent="createRoom" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="form-label"
                                        >Room Name</label
                                    >
                                    <input
                                        id="name"
                                        v-model="createForm.name"
                                        type="text"
                                        required
                                        class="form-input"
                                    />
                                </div>

                                <div>
                                    <label for="seats" class="form-label"
                                        >Capacity (Seats)</label
                                    >
                                    <input
                                        id="seats"
                                        v-model="createForm.seats"
                                        type="number"
                                        min="1"
                                        required
                                        class="form-input"
                                    />
                                </div>
                            </div>

                            <div>
                                <label for="description" class="form-label"
                                    >Description</label
                                >
                                <textarea
                                    id="description"
                                    v-model="createForm.description"
                                    rows="3"
                                    class="form-input"
                                ></textarea>
                            </div>

                            <!-- Image Upload -->
                            <div>
                                <label for="image" class="form-label"
                                    >Room Image</label
                                >
                                <div
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg"
                                >
                                    <div class="space-y-1 text-center">
                                        <svg
                                            class="mx-auto h-12 w-12 text-gray-400"
                                            stroke="currentColor"
                                            fill="none"
                                            viewBox="0 0 48 48"
                                            aria-hidden="true"
                                        >
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label
                                                for="image"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500"
                                            >
                                                <span>Upload a file</span>
                                                <input
                                                    id="image"
                                                    name="image"
                                                    type="file"
                                                    class="sr-only"
                                                    @change="handleImageUpload"
                                                    accept="image/*"
                                                />
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            PNG, JPG, GIF up to 2MB
                                        </p>
                                    </div>
                                </div>
                                <div
                                    v-if="createForm.imagePreview"
                                    class="mt-2"
                                >
                                    <img
                                        :src="createForm.imagePreview"
                                        alt="Preview"
                                        class="h-32 w-32 object-cover rounded-lg"
                                    />
                                    <button
                                        type="button"
                                        @click="clearCreateImage"
                                        class="mt-2 text-sm text-red-600 hover:text-red-800"
                                    >
                                        Remove Image
                                    </button>
                                </div>
                            </div>

                            <!-- Room Specifications -->
                            <div>
                                <label class="form-label"
                                    >Room Specifications</label
                                >
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"
                                >
                                    <div>
                                        <label
                                            for="tables"
                                            class="form-label text-sm"
                                            >Tables</label
                                        >
                                        <input
                                            id="tables"
                                            v-model="createForm.tables"
                                            type="number"
                                            min="0"
                                            class="form-input"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            for="ac_units"
                                            class="form-label text-sm"
                                            >AC Units</label
                                        >
                                        <input
                                            id="ac_units"
                                            v-model="createForm.ac_units"
                                            type="number"
                                            min="0"
                                            class="form-input"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            for="washroom_facilities"
                                            class="form-label text-sm"
                                            >Washroom Facilities</label
                                        >
                                        <input
                                            id="washroom_facilities"
                                            v-model="
                                                createForm.washroom_facilities
                                            "
                                            type="number"
                                            min="0"
                                            class="form-input"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            for="monitors"
                                            class="form-label text-sm"
                                            >Monitors</label
                                        >
                                        <input
                                            id="monitors"
                                            v-model="createForm.monitors"
                                            type="number"
                                            min="0"
                                            class="form-input"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            for="tv_screens"
                                            class="form-label text-sm"
                                            >TV Screens</label
                                        >
                                        <input
                                            id="tv_screens"
                                            v-model="createForm.tv_screens"
                                            type="number"
                                            min="0"
                                            class="form-input"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Amenities -->
                            <div>
                                <label class="form-label">Amenities</label>
                                <div
                                    class="grid grid-cols-2 md:grid-cols-3 gap-4"
                                >
                                    <label class="flex items-center">
                                        <input
                                            v-model="createForm.has_podium"
                                            type="checkbox"
                                            class="mr-2"
                                        />
                                        Podium
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            v-model="
                                                createForm.has_sound_system
                                            "
                                            type="checkbox"
                                            class="mr-2"
                                        />
                                        Sound System
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            v-model="createForm.has_projector"
                                            type="checkbox"
                                            class="mr-2"
                                        />
                                        Projector
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            v-model="createForm.has_ethernet"
                                            type="checkbox"
                                            class="mr-2"
                                        />
                                        Ethernet
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            v-model="createForm.has_wifi"
                                            type="checkbox"
                                            class="mr-2"
                                        />
                                        WiFi
                                    </label>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button
                                    type="button"
                                    @click="showCreateModal = false"
                                    class="btn-secondary"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    :disabled="createForm.processing"
                                    class="btn-primary"
                                >
                                    Create Room
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import { useForm, router, Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import toast from "@/utils/toast";

const props = defineProps({
    rooms: Array,
    stats: Object,
});

const showCreateModal = ref(false);

const createForm = useForm({
    name: "",
    description: "",
    seats: 1,
    tables: 1,
    ac_units: 1,
    washroom_facilities: 1,
    has_podium: false,
    has_sound_system: false,
    has_projector: false,
    monitors: 0,
    tv_screens: 0,
    has_ethernet: false,
    has_wifi: true,
    image: null,
    imagePreview: null,
});

const editRoom = (room) => {
    // Navigate to edit page
    window.location.href = `/admin/rooms/${room.id}/edit`;
};

const toggleRoomStatus = (room) => {
    if (
        confirm(
            `Are you sure you want to ${
                room.is_active ? "deactivate" : "activate"
            } this room?`
        )
    ) {
        // Use Inertia to make the request
        router.post(
            `/admin/rooms/${room.id}/toggle-status`,
            {},
            {
                onSuccess: () => {
                    toast.success(
                        `Room ${
                            room.is_active ? "deactivated" : "activated"
                        } successfully!`
                    );
                },
                onError: (errors) => {
                    toast.showValidationErrors(errors);
                },
            }
        );
    }
};

const deleteRoom = (room) => {
    if (
        confirm(
            `Are you sure you want to delete "${room.name}"? This action cannot be undone.`
        )
    ) {
        router.get(`/admin/rooms/${room.id}`, {
            onSuccess: () => {
                toast.success("Meeting room deleted successfully!");
            },
            onError: (errors) => {
                toast.showValidationErrors(errors);
            },
        });
    }
};

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        // Store the file for upload
        createForm.image = file;

        // Create a preview URL for display
        const reader = new FileReader();
        reader.onload = (e) => {
            // Store preview URL separately
            createForm.imagePreview = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const clearCreateImage = () => {
    createForm.image = null;
    createForm.imagePreview = null;
};

const createRoom = () => {
    // Debug: Log the form data
    console.log("Form data:", createForm.data());
    console.log("Image file:", createForm.image);

    createForm.post("/admin/rooms", {
        onSuccess: () => {
            showCreateModal.value = false;
            createForm.reset();
            createForm.imagePreview = null;
            toast.success("Meeting room created successfully!");
        },
        onError: (errors) => {
            console.log("Form errors:", errors);
            toast.showValidationErrors(errors);
        },
    });
};
</script>
