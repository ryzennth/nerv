<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3'; // Import usePage
import Navigation from '@/components/Navigation.vue';
import Footer from '@/components/Footer.vue';
import PhotoGallery from '@/components/PhotoGallery.vue'; // Komponen galeri
import axios from 'axios';
// Import komponen Pagination jika Anda punya dan ingin menggunakannya
// import Pagination from '@/components/Pagination.vue';

// Mengambil data global, termasuk info user yang login
const page = usePage();

// Props dari controller (album yang ditampilkan & daftar fotonya)
const props = defineProps({
    album: {
        type: Object,
        required: true,
    },
    photos: { // Data pagination foto
        type: Object,
        required: true,
    },
});

/**
 * Fungsi ini dipanggil ketika komponen PhotoGallery mengirim event 'photoViewed'.
 * Tugasnya mengirim request ke backend untuk mencatat hit.
 * @param {number} photoId - ID foto yang sedang dilihat.
 */
const incrementPhotoHit = async (photoId) => {
    // console.log('[Gallery/Show.vue] incrementPhotoHit dipanggil (Axios) untuk ID:', photoId);
    try {
        // Gunakan axios.post langsung ke URL rute
        await axios.post(route('photos.hit', photoId));

        // Jika request berhasil (backend mengembalikan 204 atau 200 OK)
        // console.log('[Gallery/Show.vue] Axios request sukses untuk ID:', photoId);

        // Update UI secara manual (jika diperlukan)
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
        // Tangani error jika request gagal
        console.error('[Gallery/Show.vue] Gagal mengirim request increment hit via Axios:', error);
    }
};
</script>

<template>
    <Head :title="album.title" />

    <Navigation :user="page.props.auth.user" :roles="page.props.auth.roles" />

    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <Link :href="route('gallery.index')" class="text-sm text-orange-500 hover:underline inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Back to Albums
                </Link>
                <h1 class="text-3xl lg:text-4xl font-bold mt-2 text-gray-900 dark:text-white">{{ album.title }}</h1>
                <p v-if="album.description" class="mt-2 text-base text-gray-600 dark:text-gray-400">{{ album.description }}</p>
                <p class="mt-1 text-sm text-gray-500">Viewed {{ album.views }} times</p>
            </div>

            <PhotoGallery
                v-if="photos?.data && photos.data.length > 0"
                :items="photos.data"
                galleryID="album-gallery"
                @photo-viewed="incrementPhotoHit"
            />

            <div v-else class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <p class="text-lg text-gray-500 dark:text-gray-400">No photos found in this album yet.</p>
                <svg class="mx-auto mt-4 h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>

            </div>
    </div>

    <Footer />
</template>