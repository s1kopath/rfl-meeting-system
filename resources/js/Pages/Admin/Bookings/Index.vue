<template>
  <AppLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-2xl font-bold text-gray-900">Booking Management</h2>
            <p class="text-gray-600 mt-2">Manage and approve meeting room bookings</p>
          </div>
        </div>
      </div>

      <!-- Statistics -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Bookings</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.totalBookings }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center">
            <div class="p-2 bg-yellow-100 rounded-lg">
              <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Pending</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.pendingBookings }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Confirmed</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.confirmedBookings }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center">
            <div class="p-2 bg-red-100 rounded-lg">
              <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Rejected</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.rejectedBookings }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
          <div>
            <label for="status_filter" class="form-label">Status</label>
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
              <option v-for="room in rooms" :key="room.id" :value="room.id">
                {{ room.name }}
              </option>
            </select>
          </div>

          <div>
            <label for="user_filter" class="form-label">User</label>
            <select
              id="user_filter"
              v-model="filters.user"
              class="form-input"
              @change="applyFilters"
            >
              <option value="">All Users</option>
              <option v-for="user in users" :key="user.id" :value="user.id">
                {{ user.name }}
              </option>
            </select>
          </div>

          <div class="flex items-end">
            <button @click="clearFilters" class="btn-secondary w-full">
              Clear Filters
            </button>
          </div>
        </div>
      </div>

      <!-- Bookings List -->
      <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">All Bookings</h3>
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
                <div class="flex items-center space-x-3 mb-2">
                  <h4 class="text-lg font-medium text-gray-900">{{ booking.title }}</h4>
                  <span
                    :class="[
                      'px-2 py-1 text-xs font-medium rounded-full',
                      {
                        'bg-green-100 text-green-800': booking.status === 'confirmed',
                        'bg-yellow-100 text-yellow-800': booking.status === 'pending',
                        'bg-red-100 text-red-800': booking.status === 'rejected',
                        'bg-gray-100 text-gray-800': booking.status === 'cancelled',
                      }
                    ]"
                  >
                    {{ booking.status }}
                  </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm text-gray-600 mb-2">
                  <div>
                    <span class="font-medium">User:</span> {{ booking.user?.name }}
                  </div>
                  <div>
                    <span class="font-medium">Room:</span> {{ booking.meeting_room?.name }}
                  </div>
                  <div>
                    <span class="font-medium">Date:</span> {{ booking.date }}
                  </div>
                  <div>
                    <span class="font-medium">Time:</span> {{ booking.formatted_time }}
                  </div>
                </div>

                <div class="text-sm text-gray-600 mb-2">
                  <span class="font-medium">Attendees:</span> {{ booking.attendees_count }}
                  <span v-if="booking.description" class="ml-4">
                    <span class="font-medium">Description:</span> {{ booking.description }}
                  </span>
                </div>

                <div v-if="booking.admin_notes" class="text-sm text-gray-600 mb-2">
                  <span class="font-medium">Admin Notes:</span> {{ booking.admin_notes }}
                </div>
              </div>

              <div class="flex items-center space-x-2">
                <Link
                  :href="`/admin/bookings/${booking.id}`"
                  class="btn-secondary text-sm"
                >
                  View
                </Link>
                
                <button
                  v-if="booking.status === 'pending'"
                  @click="approveBooking(booking)"
                  class="btn-primary text-sm"
                >
                  Approve
                </button>

                <button
                  v-if="booking.status === 'pending'"
                  @click="rejectBooking(booking)"
                  class="btn-danger text-sm"
                >
                  Reject
                </button>

                <button
                  v-if="booking.status === 'confirmed'"
                  @click="editBooking(booking)"
                  class="btn-secondary text-sm"
                >
                  Edit
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="bookings.length > 0" class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-700">
            Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} results
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
import { ref } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  bookings: Array,
  rooms: Array,
  users: Array,
  stats: Object,
  pagination: Object,
  filters: Object
})

const filters = ref({
  status: props.filters?.status || '',
  date: props.filters?.date || '',
  room: props.filters?.room || '',
  user: props.filters?.user || ''
})

const applyFilters = () => {
  // This would trigger a new request with filters
  console.log('Applying filters:', filters.value)
}

const clearFilters = () => {
  filters.value = {
    status: '',
    date: '',
    room: '',
    user: ''
  }
  applyFilters()
}

const loadPage = (page) => {
  // This would load a specific page
  console.log('Loading page:', page)
}

const approveBooking = (booking) => {
  if (confirm('Are you sure you want to approve this booking?')) {
    // This would approve the booking
    console.log('Approving booking:', booking.id)
  }
}

const rejectBooking = (booking) => {
  const reason = prompt('Please provide a reason for rejection:')
  if (reason !== null) {
    // This would reject the booking with reason
    console.log('Rejecting booking:', booking.id, 'Reason:', reason)
  }
}

const editBooking = (booking) => {
  // This would open edit modal or navigate to edit page
  console.log('Editing booking:', booking.id)
}
</script> 