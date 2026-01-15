<script setup>
import { useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

// Initialize form with one empty row
const form = useForm({
    items: [{ name: '', unit: 'kg', quantity: 0 }]
});

// Add a new row to the form
const addRow = () => {
    form.items.push({ name: '', unit: 'kg', quantity: 0 });
};

// Remove a row (prevent removing the last one)
const removeRow = (index) => {
    if (form.items.length > 1) {
        form.items.splice(index, 1);
    }
};

// Send data to backend
const submit = () => {
    form.post(route('items.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <section class="p-6 bg-white border-b border-gray-200 rounded-lg shadow-sm mb-6">
        <header class="mb-4">
            <h2 class="text-lg font-medium text-gray-900">Add Inventory Items</h2>
            <p class="text-sm text-gray-600">Add one or multiple items at once.</p>
        </header>

        <form @submit.prevent="submit">
            <div class="grid grid-cols-12 gap-4 mb-2 text-sm font-bold text-gray-700">
                <div class="col-span-5">Item Name</div>
                <div class="col-span-3">Unit</div>
                <div class="col-span-3">Quantity</div>
                <div class="col-span-1"></div>
            </div>

            <div v-for="(item, index) in form.items" :key="index" class="grid grid-cols-12 gap-4 mb-4 items-end">
                <div class="col-span-5">
                    <TextInput v-model="item.name" type="text" class="block w-full" placeholder="Item Name" required />
                </div>
                
                <div class="col-span-3">
                    <select v-model="item.unit" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                        <option value="kg">kg</option>
                        <option value="m">m</option>
                        <option value="cm">cm</option>
                        <option value="pcs">pcs</option>
                        <option value="l">liter</option>
                    </select>
                </div>

                <div class="col-span-3">
                    <TextInput v-model="item.quantity" type="number" step="0.01" class="block w-full" placeholder="0" required />
                </div>

                <div class="col-span-1">
                    <button v-if="form.items.length > 1" type="button" @click="removeRow(index)" class="text-red-600 font-bold px-2">X</button>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="button" @click="addRow" class="text-indigo-600 text-sm font-semibold hover:underline">+ Add Row</button>
                <PrimaryButton :disabled="form.processing">Save Items</PrimaryButton>
                <span v-if="form.recentlySuccessful" class="text-sm text-green-600">Saved!</span>
            </div>
        </form>
    </section>
</template>