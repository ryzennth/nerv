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

    <div class="bg-background dark:bg-background min-h-screen flex flex-col">
        <Navigation :user="$page.props.auth.user" :roles="$page.props.auth.roles" />

        <main class="flex-grow py-16 lg:py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl lg:text-4xl font-bold text-center mb-10 text-foreground dark:text-foreground">
                    Gallery Albums
                </h1>

                <div v-if="albums?.data && albums.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <div
                        v-for="album in albums.data"
                        :key="album.id"
                        class="bg-card dark:bg-card rounded-lg shadow-md overflow-hidden border border-border dark:border-border group transform hover:-translate-y-1 transition-transform duration-300 ease-in-out hover:shadow-xl"
                    >
                        <Link :href="route('gallery.show', album.slug)" class="block">
                            <div class="h-48 bg-neutral-light dark:bg-neutral-dark">
                                <img
                                    v-if="album.latest_photo"
                                    :src="`/storage/${album.latest_photo.file_path}`"
                                    :alt="`Thumbnail for ${album.title}`"
                                    class="w-full h-full object-cover transition-opacity duration-300 group-hover:opacity-90"
                                />
                                <div v-else class="w-full h-full flex items-center justify-center text-muted dark:text-muted text-sm italic">
                                    No Photo
                                </div>
                            </div>
                            <div class="p-4">
                                <h2 class="font-semibold text-lg text-foreground dark:text-foreground truncate group-hover:text-(--brand) transition-colors">
                                    {{ album.title }}
                                </h2>
                                <p class="text-sm text-muted dark:text-muted mt-1">
                                    {{ album.photos_count ?? 0 }} Photos
                                </p>
                                <p class="text-xs text-muted dark:text-muted mt-2">
                                    Viewed {{ album.views ?? 0 }} times
                                </p>
                            </div>
                        </Link>
                    </div>
                </div>

                <div v-else class="text-center py-16 bg-card dark:bg-card rounded-lg shadow-sm border border-border dark:border-border">
                    <p class="text-lg text-muted dark:text-muted">No albums found.</p>
                     <svg class="mx-auto mt-4 h-12 w-12 text-muted dark:text-muted opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>

                </div>
        </main>

        <Footer />
    </div>
</template>