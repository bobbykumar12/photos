<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    photos: Object,
});

const currentIndex = ref(null);
const isModalOpen = ref(false);

// Open modal at specific photo index
const openModal = (index) => {
    currentIndex.value = index;
    isModalOpen.value = true;
};

// Close modal
const closeModal = () => {
    isModalOpen.value = false;
};

// Go to next photo
const nextPhoto = () => {
    if (currentIndex.value < props.photos.data.length - 1) {
        currentIndex.value++;
    }
};

// Go to previous photo
const prevPhoto = () => {
    if (currentIndex.value > 0) {
        currentIndex.value--;
    }
};

const goToPage = (url) => {
    if (url) {
        Inertia.get(url);
    }
};
</script>

<template>
    <AppLayout title="Photo Details">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Photo Details</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-4 flex flex-wrap justify-center gap-6">
                        <div 
                            v-for="(photo, index) in photos.data" 
                            :key="photo.id" 
                            class="mb-4 cursor-pointer" 
                            @click="openModal(index)"
                        >
                            <img width="100" :src="photo.photo_url" alt="photo" class=" rounded-lg shadow-md">
                            <p class="mt-4 text-center text-xl">{{ photo.user_name }}</p>
                            <p class="text-center text-sm text-gray-600">User ID: {{ photo.user_id }}</p>
                        </div>
                    </div>

                    <div class="flex justify-center mt-6">
                        <button 
                            v-if="photos.prev_page_url" 
                            @click="goToPage(photos.prev_page_url)" 
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md mr-4" 
                        >
                            Previous
                        </button>
                        <button 
                            v-if="photos.next_page_url" 
                            @click="goToPage(photos.next_page_url)"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for single image preview -->
        <div 
            v-if="isModalOpen" 
            class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50"
            @click.self="closeModal"
        >
            <div class="relative w-full h-full flex items-center justify-center">
                <button class="absolute top-1/2 left-4 text-white text-4xl font-bold" @click.stop="prevPhoto">&lt;</button>
                
                <img 
                    :src="photos.data[currentIndex].photo_url" 
                    class="max-w-[98vw] max-h-[98vh] object-contain"
                />
                
                <button class="absolute top-1/2 right-4 text-white text-4xl font-bold" @click.stop="nextPhoto">&gt;</button>
                
                <button class="absolute top-4 right-4 text-white text-3xl font-bold bg-red-600 rounded-full w-10 h-10 flex items-center justify-center" @click="closeModal">X</button>
            </div>
        </div>

    </AppLayout>
</template>
