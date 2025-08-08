<template>
  <AppLayout>
    <Head title="Meeting Rooms" />
    <div class="space-y-6">
      <!-- Header -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-2xl font-bold text-gray-900">Meeting Rooms</h2>
            <p class="text-gray-600 mt-2">Browse available meeting rooms and their amenities</p>
          </div>
          <Link href="/bookings/create" class="btn-primary">
            Book a Room
          </Link>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label for="capacity_filter" class="form-label">Capacity</label>
            <select
              id="capacity_filter"
              v-model="filters.capacity"
              class="form-input"
              @change="applyFilters"
            >
              <option value="">Any Capacity</option>
              <option value="1-5">1-5 people</option>
              <option value="6-10">6-10 people</option>
              <option value="11-20">11-20 people</option>
              <option value="20+">20+ people</option>
            </select>
          </div>

          <div>
            <label for="amenity_filter" class="form-label">Amenity</label>
            <select
              id="amenity_filter"
              v-model="filters.amenity"
              class="form-input"
              @change="applyFilters"
            >
              <option value="">Any Amenity</option>
              <option value="projector">Projector</option>
              <option value="sound_system">Sound System</option>
              <option value="podium">Podium</option>
              <option value="wifi">WiFi</option>
              <option value="ethernet">Ethernet</option>
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

          <div class="flex items-end">
            <button @click="clearFilters" class="btn-secondary w-full">
              Clear Filters
            </button>
          </div>
        </div>
      </div>

      <!-- Rooms Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="room in rooms"
          :key="room.id"
          class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200"
        >
          <!-- Room Image -->
          <div v-if="room.image_url" class="h-48 overflow-hidden">
            <img
              :src="room.image_url"
              :alt="room.name"
              class="w-full h-full object-cover"
            />
          </div>
          <div v-else class="h-48 bg-gray-200 flex items-center justify-center">
            <div class="text-center">
              <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
              <p class="text-sm text-gray-500">No image available</p>
            </div>
          </div>

          <!-- Room Details -->
          <div class="p-6">
            <div class="flex items-center justify-between mb-2">
              <h3 class="text-xl font-semibold text-gray-900">{{ room.name }}</h3>
              <span
                :class="[
                  'px-2 py-1 text-xs font-medium rounded-full',
                  room.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                ]"
              >
                {{ room.is_active ? 'Available' : 'Unavailable' }}
              </span>
            </div>

            <p class="text-gray-600 mb-4">{{ room.description }}</p>

            <!-- Room Specifications -->
            <div class="space-y-2 mb-4">
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Capacity:</span>
                <span class="font-medium">{{ room.seats }} seats</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Tables:</span>
                <span class="font-medium">{{ room.tables }}</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">AC Units:</span>
                <span class="font-medium">{{ room.ac_units }}</span>
              </div>
            </div>

            <!-- Amenities -->
            <div class="mb-4">
              <h4 class="text-sm font-medium text-gray-900 mb-2">Amenities:</h4>
              <div class="flex flex-wrap gap-1">
                <span
                  v-for="amenity in room.amenities_list"
                  :key="amenity"
                  class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded"
                >
                  {{ amenity }}
                </span>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-2">
              <Link
                :href="`/rooms/${room.id}`"
                class="btn-secondary text-sm flex-1"
              >
                View Details
              </Link>
              <Link
                v-if="room.is_active"
                :href="`/bookings/create?room=${room.id}`"
                class="btn-primary text-sm flex-1"
              >
                Book Now
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- No Rooms Message -->
      <div v-if="rooms.length === 0" class="bg-white rounded-lg shadow-sm p-12 text-center">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No rooms found</h3>
        <p class="text-gray-500">Try adjusting your filters or check back later.</p>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  rooms: Array,
  filters: Object
})

const filters = ref({
  capacity: props.filters?.capacity || '',
  amenity: props.filters?.amenity || '',
  date: props.filters?.date || ''
})

const applyFilters = () => {
  // This would trigger a new request with filters
  console.log('Applying filters:', filters.value)
}

const clearFilters = () => {
  filters.value = {
    capacity: '',
    amenity: '',
    date: ''
  }
  applyFilters()
}
</script> 