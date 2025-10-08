<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3'; // Import router di sini

// Terima prop 'can' dari controller
defineProps({
    categories: Object,
    can: Object,
});

const destroy = (slug) => {
    if (confirm('Are you sure you want to delete this category?')) {
        router.delete(route('categories.destroy', slug));
    }
};
</script>

<template>
    <Head title="Categories" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Categories
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 dark:bg-zinc-800 dark: border-zinc-700">
                        
                        <div class="flex justify-end mb-4">
                            <button v-if="can.create_category" @click="router.get(route('categories.create'))"
                                type="button"
                                class="rounded border block appearance-none bg-green-400 border-green-400 text-white py-2 px-4 leading-tight focus:outline-none focus:bg-green focus:border-green-500">
                                + Add Category
                            </button>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 dark:bg-zinc-600 dark:border-zinc-500">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-white">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-white">Slug</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-zinc-600">
                                <tr v-for="category in categories.data" :key="category.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-black dark:text-white">
                                        {{ category.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-black dark:text-white">
                                        {{ category.slug }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link 
                                            :href="route('categories.edit', category.slug)" 
                                            class="text-indigo-600 hover:text-indigo-900"
                                        >
                                            Edit
                                        </Link>

                                       <button 
                                            @click="destroy(category.slug)"
                                            class="ml-4 text-red-600 dark:text-red-400 hover:text-red-900"
                                        >
                                            Delete
                                        </button>
                                        
                                        </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>