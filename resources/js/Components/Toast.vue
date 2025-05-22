<script setup>
import { ref, onMounted } from 'vue';

// Create a global event bus for toast messages
const notifications = ref([]);
let counter = 0;

// Event listener for showing toast messages
const showToast = (message, type = 'success', duration = 3000) => {
    const id = counter++;
    
    // Add new notification
    notifications.value.push({
        id,
        message,
        type,
    });
    
    // Auto-remove after duration
    setTimeout(() => {
        const index = notifications.value.findIndex(notification => notification.id === id);
        if (index !== -1) {
            notifications.value.splice(index, 1);
        }
    }, duration);
};

// Expose the method to the global window object
// This allows other components to use it without importing
onMounted(() => {
    window.showToast = showToast;
});

// Method to dismiss a notification
const dismiss = (id) => {
    const index = notifications.value.findIndex(notification => notification.id === id);
    if (index !== -1) {
        notifications.value.splice(index, 1);
    }
};
</script>

<template>
    <div aria-live="assertive" class="fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 z-50">
        <div class="flex flex-col items-end space-y-4 w-full">
            <transition-group
                enter-active-class="transform ease-out duration-300 transition"
                enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
                leave-active-class="transition ease-in duration-100"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div 
                    v-for="notification in notifications" 
                    :key="notification.id" 
                    class="max-w-sm w-full bg-gray-800 shadow-lg rounded-lg pointer-events-auto border-l-4 overflow-hidden"
                    :class="notification.type === 'success' ? 'border-green-500' : 'border-red-500'"
                >
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg 
                                    v-if="notification.type === 'success'"
                                    class="h-6 w-6 text-green-400" 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <svg 
                                    v-else
                                    class="h-6 w-6 text-red-400" 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm font-medium text-white">{{ notification.message }}</p>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex">
                                <button
                                    @click="dismiss(notification.id)"
                                    class="bg-gray-800 rounded-md inline-flex text-gray-400 hover:text-gray-300 focus:outline-none"
                                >
                                    <span class="sr-only">Close</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </transition-group>
        </div>
    </div>
</template>