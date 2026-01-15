<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import ItemEntryForm from '@/Components/ItemEntryForm.vue';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

// Props passed from InventoryController
const props = defineProps({
    items: Array,
    filters: Object
});

// Search Logic
const search = ref(props.filters.search || '');

// Watch for typing in search bar, wait 300ms, then reload page with search query
watch(search, debounce((value) => {
    router.get(route('dashboard'), { search: value }, {
        preserveState: true,
        replace: true,
    });
}, 300));
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Inventory Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <ItemEntryForm />

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Current Inventory</h3>
                        <input 
                            v-model="search" 
                            type="text" 
                            placeholder="Search items..." 
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        >
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="item in items" :key="item.id">
                                <td class="px-6 py-4">{{ item.name }}</td>
                                <td class="px-6 py-4 font-bold" :class="item.quantity < 10 ? 'text-red-500' : 'text-green-600'">
                                    {{ item.quantity }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ item.unit }}</td>
                                <td class="px-6 py-4">
                                    <a :href="route('items.history', item.id)" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                        View History
                                    </a>
                                </td>
                            </tr>
                            <tr v-if="items.length === 0">
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No items found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>