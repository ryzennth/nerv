<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue'; // Kita asumsikan ini ada

const form = useForm({
    name: '',
});

const submit = () => {
    form.post(route('categories.store'));
};
</script>

<template>
    <Head title="Create Category" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Create Category
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <form @submit.prevent="submit">
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Name</label>

                                <input 
                                    id="name" 
                                    type="text" 
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                    v-model="form.name" 
                                    required 
                                    autofocus 
                                />
                                
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <button 
                                    type="submit"
                                    :class="{ 'opacity-25': form.processing }" 
                                    :disabled="form.processing"
                                    class="rounded border bg-green-400 border-green-400 text-white py-2 px-4 leading-tight hover:bg-green-500 focus:outline-none focus:bg-green-500 focus:border-green-600">
                                    Create Category
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>