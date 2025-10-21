<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import PhotoSwipeLightbox from 'photoswipe/lightbox';
import 'photoswipe/style.css';

const props = defineProps({
    items: { // Array foto [ { file_path: '...', caption: '...', id: ... }, ... ]
        type: Array,
        required: true,
    },
    galleryID: { // ID unik untuk galeri ini
        type: String,
        required: true,
    },
});

const emit = defineEmits(['photoViewed']); // Event saat foto dilihat

let lightbox = null;

onMounted(() => {
    lightbox = new PhotoSwipeLightbox({
        gallery: `#${props.galleryID}`, // Selector CSS untuk container galeri
        children: 'a',                // Selector CSS untuk link foto di dalam container
        pswpModule: () => import('photoswipe'), // Load modul PhotoSwipe saat dibutuhkan
        // Atur agar ukuran gambar diambil dari atribut data
        thumbSelector: 'img',
        allowSizeAndZoomCache: false,
    });

    // Tambahkan listener untuk event 'contentLoad' atau 'change'
    lightbox.on('change', () => {
         const currSlide = lightbox.pswp.currSlide;
         if (currSlide && currSlide.data.id) {
            // Kirim event dengan ID foto yang sedang dilihat
            emit('photoViewed', currSlide.data.id);
        }
    });


    lightbox.init();
});

onUnmounted(() => {
    if (lightbox) {
        lightbox.destroy();
        lightbox = null;
    }
});

// Helper untuk mendapatkan dimensi gambar (jika memungkinkan)
// Ini opsional tapi direkomendasikan untuk performa PhotoSwipe
// const getImageDimensions = (path) => {
//     // Implementasi untuk mendapatkan width/height, misal dari backend atau analisa nama file
//     // Contoh sederhana:
//     return { width: 1200, height: 800 }; // Ganti dengan logika sebenarnya
// }
</script>

<template>
    <div :id="galleryID" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        <a
            v-for="(item, index) in items"
            :key="item.id"
            :href="`/storage/${item.file_path}`"
            :data-pswp-width="1200"  
            :data-pswp-height="800" 
            :data-id="item.id"      
            target="_blank"
            rel="noreferrer"
            class="aspect-square bg-gray-200 dark:bg-gray-800 rounded-lg overflow-hidden cursor-pointer group relative block"
        >
            <img
                :src="`/storage/${item.file_path}`"
                :alt="item.caption || 'Gallery image'"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
            />
            <div v-if="item.caption" class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                {{ item.caption }} (Hits: {{ item.hits }})
            </div>
        </a>
    </div>
</template>