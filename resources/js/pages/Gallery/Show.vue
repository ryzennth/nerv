<script setup>
// Script setup tetap sama persis seperti yang Anda berikan
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Navigation from '@/components/Navigation.vue';
import Footer from '@/components/Footer.vue';
import PhotoGallery from '@/components/PhotoGallery.vue';
import axios from 'axios';
// import Pagination from '@/components/Pagination.vue';

const page = usePage(); // Biarkan ini jika diperlukan Navigation

const props = defineProps({
    album: {
        type: Object,
        required: true,
    },
    photos: {
        type: Object,
        required: true,
    },
});

// Fungsi incrementPhotoHit tetap sama persis
const incrementPhotoHit = async (photoId) => {
    try {
        await axios.post(route('photos.hit', photoId));
        const photoIndex = props.photos.data.findIndex(p => p.id === photoId);
        if (photoIndex !== -1) {
            const photo = props.photos.data[photoIndex];
            if (photo.hasOwnProperty('hits')) {
                photo.hits++;
            } else {
                photo.hits = 1;
            }
        }
    } catch (error) {
        console.error('[Gallery/Show.vue] Gagal mengirim request increment hit via Axios:', error);
    }
};
</script>

<template>
    <Head :title="album.title" />

    <Navigation :user="$page.props.auth.user" :roles="$page.props.auth.roles" />

    <div class="bg-background dark:bg-background min-h-screen py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <Link :href="route('gallery.index')" class="text-sm text-primary hover:opacity-80 inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Back to Albums
                </Link>
                <h1 class="text-3xl lg:text-4xl font-bold mt-2 text-foreground dark:text-foreground">
                    {{ album.title }}
                </h1>
                <p v-if="album.description" class="mt-2 text-base text-muted dark:text-muted">
                    {{ album.description }}
                </p>
                <p class="mt-1 text-sm text-muted dark:text-muted">
                    Viewed {{ album.views }} times
                </p>
            </div>

            <PhotoGallery
                v-if="photos?.data && photos.data.length > 0"
                :items="photos.data"
                galleryID="album-gallery"
                @photo-viewed="incrementPhotoHit"
            />

            <div v-else class="text-center py-16 bg-card dark:bg-card rounded-lg shadow-sm border border-border dark:border-border">
                <p class="text-lg text-muted dark:text-muted">
                    No photos found in this album yet.
                </p>
                <svg class="mx-auto mt-4 h-12 w-12 text-muted dark:text-muted opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>

            </div>
    </div>

    <Footer />
</template>