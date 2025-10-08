<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    tags: Object,
    can: Object,
});

const destroy = (slug) => {
    if (confirm('Are you sure you want to delete this tag?')) {
        router.delete(route('tags.destroy', slug));
    }
};
</script>

<template>
    <Head title="Tags" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Tags
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex justify-end mb-4">
                            <button v-if="can.create_tag" @click="router.get(route('tags.create'))"
                                type="button"
                                class="rounded border block appearance-none bg-green-400 border-green-400 text-white py-2 px-4 leading-tight focus:outline-none focus:bg-green focus:border-green-500">
                                + Add Tag
                            </button>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Slug</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>

                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200">
                                <tr v-for="tag in tags.data" :key="tag.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ tag.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ tag.slug }}
                                    </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <Link 
                                    :href="route('tags.edit', tag.slug)" 
                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900"
                                >
                                    Edit
                                </Link>
                                <button 
                                    @click="destroy(tag.slug)"
                                    class="ml-4 text-red-600 dark:text-red-400 hover:text-red-900"
                                >
                                    Delete
                                </button>
                            </td>
                                </tr>
                                <tr v-if="tags.data.length === 0">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" colspan="3">No tags found.</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>