<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    item: Object,
    logs: Array
});
</script>

<template>
    <Head :title="`History - ${item.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    History for: {{ item.name }}
                </h2>
                <Link :href="route('dashboard')" class="text-sm text-gray-600 hover:text-gray-900 underline">
                    Back to Dashboard
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="mb-4">
                        <span class="text-gray-600">Current Stock: </span>
                        <span class="font-bold text-lg">{{ item.quantity }} {{ item.unit }}</span>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">User</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Action</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Change</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Note</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="log in logs" :key="log.id">
                                <td class="px-4 py-2 text-sm">{{ new Date(log.created_at).toLocaleString() }}</td>
                                <td class="px-4 py-2 text-sm">{{ log.user.name }}</td>
                                <td class="px-4 py-2 text-sm font-bold uppercase" 
                                    :class="log.type === 'add' ? 'text-green-600' : 'text-red-600'">
                                    {{ log.type }}
                                </td>
                                <td class="px-4 py-2 text-sm font-mono">
                                    {{ log.type === 'add' ? '+' : '-' }}{{ log.quantity }}
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-500 italic">{{ log.note || '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>