<template>
    <AppLayout>
        <Head title="User Management" />
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            User Management
                        </h2>
                        <p class="text-gray-600 mt-2">
                            Manage system users and their roles
                        </p>
                    </div>
                    <button @click="showCreateModal = true" class="btn-primary">
                        Add New User
                    </button>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="role_filter" class="form-label">Role</label>
                        <select
                            id="role_filter"
                            v-model="filters.role"
                            class="form-input"
                            @change="applyFilters"
                        >
                            <option value="">All Roles</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="user">User</option>
                        </select>
                    </div>

                    <div>
                        <label for="search" class="form-label">Search</label>
                        <input
                            id="search"
                            v-model="filters.search"
                            type="text"
                            class="form-input"
                            placeholder="Search by name or email"
                            @input="applyFilters"
                        />
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

            <!-- Users List -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Users</h3>
                </div>

                <div v-if="users.length === 0" class="p-6 text-center">
                    <p class="text-gray-500">No users found.</p>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    User
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Role
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Created
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="user in users" :key="user.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center"
                                            >
                                                <span
                                                    class="text-sm font-medium text-gray-700"
                                                >
                                                    {{
                                                        user.name
                                                            .charAt(0)
                                                            .toUpperCase()
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ user.name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ user.email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="[
                                            'px-2 py-1 text-xs font-medium rounded-full',
                                            {
                                                'bg-red-100 text-red-800':
                                                    user.role === 'admin',
                                                'bg-blue-100 text-blue-800':
                                                    user.role === 'staff',
                                                'bg-green-100 text-green-800':
                                                    user.role === 'user',
                                            },
                                        ]"
                                    >
                                        {{ user.role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900"
                                        >Active</span
                                    >
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{ user.created_at }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                >
                                    <div
                                        class="flex items-center justify-end space-x-2"
                                    >
                                        <button
                                            @click="editUser(user)"
                                            class="text-blue-600 hover:text-blue-900"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            v-if="
                                                user.id !==
                                                $page.props.auth.user.id
                                            "
                                            @click="deleteUser(user)"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="users.length > 0"
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

            <!-- Create User Modal -->
            <div
                v-if="showCreateModal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            >
                <div
                    class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
                >
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Add New User
                        </h3>
                        <form @submit.prevent="createUser" class="space-y-4">
                            <div>
                                <label for="name" class="form-label"
                                    >Name</label
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
                                <label for="email" class="form-label"
                                    >Email</label
                                >
                                <input
                                    id="email"
                                    v-model="createForm.email"
                                    type="email"
                                    required
                                    class="form-input"
                                />
                            </div>

                            <div>
                                <label for="password" class="form-label"
                                    >Password</label
                                >
                                <input
                                    id="password"
                                    v-model="createForm.password"
                                    type="password"
                                    required
                                    class="form-input"
                                />
                            </div>

                            <div>
                                <label for="role" class="form-label"
                                    >Role</label
                                >
                                <select
                                    id="role"
                                    v-model="createForm.role"
                                    required
                                    class="form-input"
                                >
                                    <option value="user">User</option>
                                    <option value="staff">Staff</option>
                                    <option value="admin">Admin</option>
                                </select>
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
                                    Create User
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
import { useForm, Link, Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import toast from "@/utils/toast";

const props = defineProps({
    users: Array,
    pagination: Object,
    filters: Object,
});

const showCreateModal = ref(false);
const filters = ref({
    role: props.filters?.role || "",
    search: props.filters?.search || "",
});

const createForm = useForm({
    name: "",
    email: "",
    password: "",
    role: "user",
});

const applyFilters = () => {
    // This would trigger a new request with filters
    console.log("Applying filters:", filters.value);
};

const clearFilters = () => {
    filters.value = {
        role: "",
        search: "",
    };
    applyFilters();
};

const loadPage = (page) => {
    // This would load a specific page
    console.log("Loading page:", page);
};

const editUser = (user) => {
    // This would open edit modal or navigate to edit page
    console.log("Editing user:", user.id);
};

const deleteUser = (user) => {
    if (confirm("Are you sure you want to delete this user?")) {
        // This would delete the user
        console.log("Deleting user:", user.id);
        toast.success("User deleted successfully!");
    }
};

const createUser = () => {
    createForm.post("/admin/users", {
        onSuccess: () => {
            showCreateModal.value = false;
            createForm.reset();
            toast.success("User created successfully!");
        },
        onError: (errors) => {
            toast.showValidationErrors(errors);
        },
    });
};
</script>
