<template>
    <AppLayout>
        <Head title="Test Toasts" />
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-2xl font-bold text-gray-900">
                    Toast Notification Test
                </h2>
                <p class="text-gray-600 mt-2">
                    Test different types of toast notifications
                </p>
            </div>

            <!-- Test Buttons -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"
                >
                    <button @click="testSuccess" class="btn-primary">
                        Test Success Toast
                    </button>

                    <button @click="testError" class="btn-danger">
                        Test Error Toast
                    </button>

                    <button
                        @click="testWarning"
                        class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200"
                    >
                        Test Warning Toast
                    </button>

                    <button
                        @click="testInfo"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200"
                    >
                        Test Info Toast
                    </button>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button
                        @click="testValidationErrors"
                        class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200"
                    >
                        Test Validation Errors
                    </button>

                    <button
                        @click="testMultipleToasts"
                        class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200"
                    >
                        Test Multiple Toasts (Stacking)
                    </button>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-blue-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">
                    Instructions:
                </h3>
                <ul class="text-blue-800 space-y-1">
                    <li>
                        • Click the buttons above to test different toast types
                    </li>
                    <li>
                        • Toasts should appear as floating popups in the
                        top-right corner
                    </li>
                    <li>
                        • They should appear above all other content (z-index:
                        99999)
                    </li>
                    <li>
                        • Each toast should have different colors based on type
                    </li>
                    <li>• Toasts should auto-dismiss after 5-7 seconds</li>
                    <li>• You can manually close them with the X button</li>
                    <li>
                        • <strong>New:</strong> Multiple toasts should stack
                        properly (older ones slide down)
                    </li>
                    <li>
                        • <strong>New:</strong> New toasts should slide in from
                        the right
                    </li>
                </ul>
            </div>

            <!-- Expected Behavior -->
            <div class="bg-green-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-green-900 mb-2">
                    Expected Stacking Behavior:
                </h3>
                <ul class="text-green-800 space-y-1">
                    <li>• First toast appears at top-right</li>
                    <li>• Second toast appears below the first</li>
                    <li>• Third toast appears below the second</li>
                    <li>• Older toasts slide down when new ones appear</li>
                    <li>• New toasts slide in from the right</li>
                    <li>• Each toast maintains proper spacing</li>
                </ul>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import toast from "@/utils/toast";

const testSuccess = () => {
    toast.success("This is a success message! 🎉");
};

const testError = () => {
    toast.error("This is an error message! ❌");
};

const testWarning = () => {
    toast.warning("This is a warning message! ⚠️");
};

const testInfo = () => {
    toast.info("This is an info message! ℹ️");
};

const testValidationErrors = () => {
    const errors = {
        name: ["The name field is required."],
        email: [
            "The email field is required.",
            "The email must be a valid email address.",
        ],
        password: [
            "The password field is required.",
            "The password must be at least 8 characters.",
        ],
    };
    toast.showValidationErrors(errors);
};

const testMultipleToasts = () => {
    // Show multiple toasts quickly to test stacking
    toast.success("First toast - Success! 🎉");

    setTimeout(() => {
        toast.error("Second toast - Error! ❌");
    }, 500);

    setTimeout(() => {
        toast.warning("Third toast - Warning! ⚠️");
    }, 1000);

    setTimeout(() => {
        toast.info("Fourth toast - Info! ℹ️");
    }, 1500);

    setTimeout(() => {
        toast.success("Fifth toast - Another success! 🎉");
    }, 2000);
};
</script>
