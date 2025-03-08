<script setup>
import { defineProps } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

// Accepting `photos` as props from the backend (Inertia)
const props = defineProps({
  users: Array,  // Now we're receiving a list of unique users
});

// Function to handle the view more action
const viewMore = (userId) => {
  // Open a new window with the URL containing the userId to display their photos
  window.open(`/user/${userId}/photos`, '_blank');
};
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Users and Photos</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- Users Table Section -->
                    <div class="text-center p-4">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">User List</h2>
                    </div>

                    <!-- Users Table -->
                    <div class="overflow-x-auto py-4">
                        <table class="min-w-full table-auto">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">User Name</th>
                                    <th class="px-4 py-2 text-left">User ID</th>
                                    <th class="px-4 py-2 text-left">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Looping through the unique users passed from the backend -->
                                <tr v-for="user in props.users" :key="user.user_id">
                                    <td class="px-4 py-2">{{ user.user_name }}</td>
                                    <td class="px-4 py-2">{{ user.user_id }}</td>
                                    <td class="px-4 py-2">
                                        <!-- View More button to navigate to the user's photo gallery -->
                                        <button @click="viewMore(user.user_id)" class="text-white bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded">
                                            View More
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center py-4">
                        <button 
                            v-if="props.users.prev_page_url" 
                            @click="props.users.prev_page_url" 
                            class="px-4 py-2 bg-gray-300 text-black rounded-lg hover:bg-gray-400">
                            Previous
                        </button>
                        <button 
                            v-if="props.users.next_page_url" 
                            @click="props.users.next_page_url" 
                            class="px-4 py-2 bg-gray-300 text-black rounded-lg hover:bg-gray-400">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
