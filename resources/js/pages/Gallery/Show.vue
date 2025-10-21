<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Navigation from '@/components/Navigation.vue';
import Footer from '@/components/Footer.vue';
import PhotoGallery from '@/components/PhotoGallery.vue'; // Komponen untuk menampilkan grid + lightbox

// Props dari controller
const props = defineProps({
    album: Object,
    photos: Object, // Data pagination foto
});

// Fungsi untuk increment hits (dipanggil saat event @photo-viewed diterima dari PhotoGallery)
const incrementPhotoHit = (photoId) => {
    // Kirim request POST ke backend tanpa reload halaman
    router.post(route('photos.hit', photoId), {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            // Update counter hits di frontend secara manual agar langsung terlihat
            const photoIndex = props.photos.data.findIndex(p => p.id === photoId);
            if (photoIndex !== -1) {
                // Pastikan properti hits ada sebelum di-increment
                if (props.photos.data[photoIndex].hasOwnProperty('hits')) {
                    props.photos.data[photoIndex].hits++;
                } else {
                    // Jika belum ada, inisialisasi dengan 1
                    props.photos.data[photoIndex].hits = 1;
                }
            }
        },
        onError: (errors) => {
            console.error('Failed to increment photo hit:', errors);
        }
    });
};
</script>

<template>
    <Head :title="album.title" />

    <Navigation :user="$page.props.auth.user" :roles="$page.props.auth.roles" />

    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <Link :href="route('gallery.index')" class="text-orange-500 hover:underline mb-4 inline-block">&larr; Back to Albums</Link>
            <h1 class="text-3xl font-bold mb-2 text-gray-900 dark:text-white">{{ album.title }}</h1>
            <p v-if="album.description" class="text-gray-600 dark:text-gray-400 mb-6">{{ album.description }}</p>
            <p class="text-sm text-gray-500 mb-8">Viewed {{ album.views }} times</p>

            <PhotoGallery
                v-if="photos.data && photos.data.length > 0"
                :items="photos.data"
                galleryID="album-gallery"
                @photo-viewed="incrementPhotoHit"
            />

            <div v-else class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <p class="text-lg text-gray-500 dark:text-gray-400">No photos in this album yet.</p>
                </div>

            </div>
    </div>

    <Footer />
</template>