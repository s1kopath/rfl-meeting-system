<template>
    <AppLayout>
        <Head title="Edit Meeting Room" />
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            Edit Meeting Room
                        </h2>
                        <p class="text-gray-600 mt-2">
                            Update meeting room details and amenities
                        </p>
                    </div>
                    <Link href="/admin/rooms" class="btn-secondary">
                        Back to Rooms
                    </Link>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <form @submit.prevent="updateRoom" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="form-label"
                                >Room Name</label
                            >
                            <input
                                id="name"
                                v-model="form.name"
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
                                v-model="form.seats"
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
                            v-model="form.description"
                            rows="3"
                            class="form-input"
                        ></textarea>
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label for="image" class="form-label">Room Image</label>
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

                        <!-- Current Image Preview -->
                        <div v-if="room.image_url" class="mt-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">
                                Current Image:
                            </p>
                            <img
                                :src="room.image_url"
                                alt="Current room image"
                                class="h-32 w-32 object-cover rounded-lg border"
                            />
                            <button
                                type="button"
                                @click="clearImage"
                                class="mt-2 text-sm text-red-600 hover:text-red-800"
                            >
                                Remove Image
                            </button>
                        </div>

                        <!-- New Image Preview -->
                        <div
                            v-if="
                                form.imagePreview &&
                                form.imagePreview !== room.image_url
                            "
                            class="mt-4"
                        >
                            <p class="text-sm font-medium text-gray-700 mb-2">
                                New Image Preview:
                            </p>
                            <img
                                :src="form.imagePreview"
                                alt="New image preview"
                                class="h-32 w-32 object-cover rounded-lg border"
                            />
                            <button
                                type="button"
                                @click="clearImage"
                                class="mt-2 text-sm text-red-600 hover:text-red-800"
                            >
                                Remove New Image
                            </button>
                        </div>
                    </div>

                    <!-- Room Specifications -->
                    <div>
                        <label class="form-label">Room Specifications</label>
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"
                        >
                            <div>
                                <label for="tables" class="form-label text-sm"
                                    >Tables</label
                                >
                                <input
                                    id="tables"
                                    v-model="form.tables"
                                    type="number"
                                    min="0"
                                    class="form-input"
                                />
                            </div>
                            <div>
                                <label for="ac_units" class="form-label text-sm"
                                    >AC Units</label
                                >
                                <input
                                    id="ac_units"
                                    v-model="form.ac_units"
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
                                    v-model="form.washroom_facilities"
                                    type="number"
                                    min="0"
                                    class="form-input"
                                />
                            </div>
                            <div>
                                <label for="monitors" class="form-label text-sm"
                                    >Monitors</label
                                >
                                <input
                                    id="monitors"
                                    v-model="form.monitors"
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
                                    v-model="form.tv_screens"
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
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <label class="flex items-center">
                                <input
                                    v-model="form.has_podium"
                                    type="checkbox"
                                    class="mr-2"
                                />
                                Podium
                            </label>
                            <label class="flex items-center">
                                <input
                                    v-model="form.has_sound_system"
                                    type="checkbox"
                                    class="mr-2"
                                />
                                Sound System
                            </label>
                            <label class="flex items-center">
                                <input
                                    v-model="form.has_projector"
                                    type="checkbox"
                                    class="mr-2"
                                />
                                Projector
                            </label>
                            <label class="flex items-center">
                                <input
                                    v-model="form.has_ethernet"
                                    type="checkbox"
                                    class="mr-2"
                                />
                                Ethernet
                            </label>
                            <label class="flex items-center">
                                <input
                                    v-model="form.has_wifi"
                                    type="checkbox"
                                    class="mr-2"
                                />
                                WiFi
                            </label>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="form-label">Room Status</label>
                        <div class="flex items-center">
                            <input
                                id="is_active"
                                v-model="form.is_active"
                                type="checkbox"
                                class="mr-2"
                            />
                            <label
                                for="is_active"
                                class="text-sm text-gray-700"
                            >
                                Active (Available for booking)
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <Link href="/admin/rooms" class="btn-secondary">
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="btn-primary"
                        >
                            Update Room
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, Head } from "@inertiajs/vue3";
import toast from "@/utils/toast";

const props = defineProps({
    room: Object,
});

const form = useForm({
    name: props.room.name,
    description: props.room.description || "",
    seats: props.room.seats,
    tables: props.room.tables,
    ac_units: props.room.ac_units,
    washroom_facilities: props.room.washroom_facilities,
    has_podium: props.room.has_podium,
    has_sound_system: props.room.has_sound_system,
    has_projector: props.room.has_projector,
    monitors: props.room.monitors,
    tv_screens: props.room.tv_screens,
    has_ethernet: props.room.has_ethernet,
    has_wifi: props.room.has_wifi,
    is_active: props.room.is_active,
    image: null,
    imagePreview: props.room.image_url || null,
});

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        // Store the file for upload
        form.image = file;

        // Create a preview URL for display
        const reader = new FileReader();
        reader.onload = (e) => {
            // Store preview URL separately
            form.imagePreview = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const clearImage = () => {
    form.image = null;
    form.imagePreview = null;
};

const updateRoom = () => {
    form.put(`/admin/rooms/${props.room.id}`, {
        onSuccess: () => {
            toast.success("Meeting room updated successfully!");
        },
        onError: (errors) => {
            toast.showValidationErrors(errors);
        },
    });
};
</script>
