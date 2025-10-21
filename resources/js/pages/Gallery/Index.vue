<script setup>
import { Head, Link } from '@inertiajs/vue3';
// Import layout utamamu jika ada (misal: AppLayout atau Navigation + Footer)
import Navigation from '@/components/Navigation.vue';
import Footer from '@/components/Footer.vue';

defineProps({
    albums: Object, // Data pagination dari controller
});
</script>

<template>
    <Head title="Gallery Albums" />
    <Navigation :user="$page.props.auth.user" :roles="$page.props.auth.roles" />

    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-center mb-8 text-gray-900 dark:text-white">Gallery Albums</h1>

            <div v-if="albums.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div v-for="album in albums.data" :key="album.id" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <Link :href="route('gallery.show', album.slug)">
                        <div class="h-48 bg-gray-200 dark:bg-gray-700">
                            <img
                                v-if="album.latest_photo"
                                :src="`/storage/${album.latest_photo.file_path}`"
                                :alt="`Thumbnail for ${album.title}`"
                                class="w-full h-full object-cover"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center text-gray-500 text-sm">
                                No Photo
                            </div>
                        </div>
                        <div class="p-4">
                            <h2 class="font-semibold text-lg text-gray-900 dark:text-white truncate">{{ album.title }}</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ album.photos_count }} Photos</p>
                            <p class="text-xs text-gray-400 mt-2">Viewed {{ album.views }} times</p>
                        </div>
                    </Link>
                </div>
            </div>
            <div v-else class="text-center py-16">
                <p class="text-lg text-gray-500 dark:text-gray-400">No albums found.</p>
            </div>
        </div>
    </div>

    <Footer />
</template>