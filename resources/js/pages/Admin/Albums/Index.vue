<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue'; // Sesuaikan jika layout admin berbeda
// Import komponen Pagination jika Anda punya
// import Pagination from '@/components/Pagination.vue';

const props = defineProps({
    albums: Object, // Data pagination dari AlbumController@index
});

// Fungsi untuk menghapus album (dengan konfirmasi)
const destroy = (slug) => {
    if (confirm('Are you sure you want to delete this album and all its photos? This action cannot be undone.')) {
        router.delete(route('albums.destroy', slug));
    }
};
</script>

<template>
    <Head title="Manage Albums" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Manage Albums
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <div class="flex justify-end mb-4">
                            <Link :href="route('albums.create')" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 disabled:opacity-25 transition">
                                + Add New Album
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Slug</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Photo Count</th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="album in albums.data" :key="album.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ album.title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ album.slug }}
                                        </td>
                                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ album.photos_count ?? 'N/A' }} </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <Link :href="route('albums.photos.index', album.slug)" class="text-blue-600 dark:text-blue-400 hover:text-blue-900">Manage Photos</Link>
                                            <Link :href="route('albums.edit', album.slug)" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900">Edit</Link>
                                            <button @click="destroy(album.slug)" class="text-red-600 dark:text-red-400 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                    <tr v-if="albums.data.length === 0">
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500" colspan="4">No albums found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                         </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>