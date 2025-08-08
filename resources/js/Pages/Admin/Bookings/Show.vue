<template>
    <AppLayout>
        <Head title="Booking Details - Admin" />
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            Booking Details
                        </h2>
                        <p class="text-gray-600 mt-2">
                            View and manage booking information
                        </p>
                    </div>
                    <Link href="/admin/bookings" class="btn-secondary">
                        Back to Bookings
                    </Link>
                </div>
            </div>

            <!-- Booking Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Basic Information -->
                        <div>
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Meeting Information
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Title:</span>
                                    <span class="font-medium">{{
                                        booking.title
                                    }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
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
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Date:</span>
                                    <span class="font-medium">{{
                                        booking.date
                                    }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Time:</span>
                                    <span class="font-medium">{{
                                        booking.formatted_time
                                    }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600"
                                        >Attendees:</span
                                    >
                                    <span class="font-medium">{{
                                        booking.attendees_count
                                    }}</span>
                                </div>
                                <div
                                    v-if="booking.description"
                                    class="flex justify-between"
                                >
                                    <span class="text-gray-600"
                                        >Description:</span
                                    >
                                    <span
                                        class="font-medium text-right max-w-xs"
                                        >{{ booking.description }}</span
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Room Information -->
                        <div>
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Room Information
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Room:</span>
                                    <span class="font-medium">{{
                                        booking.meeting_room?.name
                                    }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Capacity:</span>
                                    <span class="font-medium"
                                        >{{
                                            booking.meeting_room?.seats
                                        }}
                                        seats</span
                                    >
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600"
                                        >Room Status:</span
                                    >
                                    <span
                                        :class="[
                                            'px-2 py-1 text-xs font-medium rounded-full',
                                            booking.meeting_room?.is_active
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-red-100 text-red-800',
                                        ]"
                                    >
                                        {{
                                            booking.meeting_room?.is_active
                                                ? "Active"
                                                : "Inactive"
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- User Information -->
                        <div>
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                User Information
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Name:</span>
                                    <span class="font-medium">{{
                                        booking.user?.name
                                    }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-medium">{{
                                        booking.user?.email
                                    }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Role:</span>
                                    <span class="font-medium capitalize">{{
                                        booking.user?.role
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Admin Actions -->
                        <div>
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Admin Actions
                            </h3>
                            <div class="space-y-3">
                                <div
                                    v-if="booking.status === 'pending'"
                                    class="flex space-x-3"
                                >
                                    <button
                                        @click="approveBooking"
                                        class="btn-primary flex-1"
                                    >
                                        Approve Booking
                                    </button>
                                    <button
                                        @click="showRejectModal = true"
                                        class="btn-danger flex-1"
                                    >
                                        Reject Booking
                                    </button>
                                </div>
                                <div v-else class="text-sm text-gray-600">
                                    <p v-if="booking.status === 'confirmed'">
                                        ✅ This booking has been approved
                                    </p>
                                    <p
                                        v-else-if="
                                            booking.status === 'rejected'
                                        "
                                    >
                                        ❌ This booking has been rejected
                                    </p>
                                    <p
                                        v-else-if="
                                            booking.status === 'cancelled'
                                        "
                                    >
                                        🚫 This booking has been cancelled
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Notes -->
                        <div>
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Admin Notes
                            </h3>
                            <div
                                v-if="booking.admin_notes"
                                class="bg-gray-50 p-4 rounded-lg"
                            >
                                <p class="text-gray-700">
                                    {{ booking.admin_notes }}
                                </p>
                            </div>
                            <div v-else class="text-gray-500 italic">
                                No admin notes available
                            </div>
                        </div>

                        <!-- Booking Timeline -->
                        <div>
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Booking Timeline
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-2 h-2 bg-blue-500 rounded-full"
                                    ></div>
                                    <div>
                                        <p class="text-sm font-medium">
                                            Booking Created
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ booking.created_at }}
                                        </p>
                                    </div>
                                </div>
                                <div
                                    v-if="booking.status !== 'pending'"
                                    class="flex items-center space-x-3"
                                >
                                    <div
                                        class="w-2 h-2 bg-green-500 rounded-full"
                                    ></div>
                                    <div>
                                        <p class="text-sm font-medium">
                                            {{
                                                booking.status === "confirmed"
                                                    ? "Booking Approved"
                                                    : booking.status ===
                                                      "rejected"
                                                    ? "Booking Rejected"
                                                    : "Booking Cancelled"
                                            }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ booking.updated_at }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div
            v-if="showRejectModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        >
            <div
                class="relative top-10 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white"
            >
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Reject Booking
                    </h3>
                    <form @submit.prevent="rejectBooking" class="space-y-4">
                        <div>
                            <label for="admin_notes" class="form-label">
                                Reason for Rejection
                            </label>
                            <textarea
                                id="admin_notes"
                                v-model="rejectForm.admin_notes"
                                rows="4"
                                class="form-input"
                                placeholder="Please provide a reason for rejecting this booking..."
                                required
                            ></textarea>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button
                                type="button"
                                @click="showRejectModal = false"
                                class="btn-secondary"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="rejectForm.processing"
                                class="btn-danger"
                            >
                                Reject Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import { useForm, Link, router, Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import toast from "@/utils/toast";

const props = defineProps({
    booking: Object,
});

const showRejectModal = ref(false);

const rejectForm = useForm({
    admin_notes: "",
});

const approveBooking = () => {
    if (confirm("Are you sure you want to approve this booking?")) {
        router.post(
            `/admin/bookings/${props.booking.id}/approve`,
            {},
            {
                onSuccess: () => {
                    toast.success("Booking approved successfully!");
                },
                onError: (errors) => {
                    toast.showValidationErrors(errors);
                },
            }
        );
    }
};

const rejectBooking = () => {
    rejectForm.post(`/admin/bookings/${props.booking.id}/reject`, {
        onSuccess: () => {
            showRejectModal.value = false;
            rejectForm.reset();
            toast.success("Booking rejected successfully!");
        },
        onError: (errors) => {
            toast.showValidationErrors(errors);
        },
    });
};
</script>
