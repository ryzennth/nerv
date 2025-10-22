<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue'; // Tambahkan watch
import PhotoSwipeLightbox from 'photoswipe/lightbox';
import 'photoswipe/style.css'; // Pastikan CSS PhotoSwipe diimpor

const props = defineProps({
    items: {
        type: Array,
        required: true,
        default: () => [] // Tambahkan default value untuk keamanan
    },
    galleryID: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(['photoViewed']);

const galleryElement = ref(null); // Ref untuk elemen galeri utama
let lightbox = null;

onMounted(() => {
    // Pastikan elemen galeri sudah ada sebelum inisialisasi
    if (!galleryElement.value) {
        console.error('Gallery element not found!');
        return;
    }

    // Inisialisasi Lightbox
    lightbox = new PhotoSwipeLightbox({
        gallery: `#${props.galleryID}`,
        children: 'a',
        pswpModule: () => import('photoswipe'),
        // Menggunakan placeholder ukuran, idealnya ambil dari data asli
        // Fungsi ini akan dijalankan PhotoSwipe untuk setiap item
        dataSource: props.items.map(item => ({
            src: `/storage/${item.file_path}`,
            width: item.width || 1200,   // Ambil width asli jika ada, fallback ke 1200
            height: item.height || 800, // Ambil height asli jika ada, fallback ke 800
            id: item.id, // Menyimpan ID di data source
            alt: item.caption || 'Gallery image'
        })),
        // Opsi tambahan jika diperlukan
         showHideAnimationType: 'fade', // Animasi sederhana
    });

    // Listener saat slide berubah (setelah slide sepenuhnya ditampilkan)
    lightbox.on('change', () => {
        // Aktifkan log ini jika perlu debug
        // console.log('PhotoSwipe slide changed!');

        // 1. Dapatkan indeks slide saat ini (lebih andal)
        const currentIndex = lightbox.pswp?.currIndex;

        // Aktifkan log ini jika perlu debug
        // console.log('Current slide index:', currentIndex);

        // 2. Periksa apakah indeks valid dan ada item yang sesuai di props
        if (currentIndex !== undefined && currentIndex >= 0 && props.items && props.items[currentIndex]) {
            // 3. Ambil ID foto dari array 'items' asli menggunakan indeks
            const photoId = props.items[currentIndex].id;

            // Aktifkan log ini jika perlu debug
            // console.log('Emitting photoViewed for ID:', photoId);

            // 4. Kirim event dengan ID yang ditemukan
            emit('photoViewed', photoId);
        } else {
            // Pesan jika indeks tidak valid atau item tidak ditemukan
            console.warn('Could not get valid index or photo item for the current slide.');
        }
    });

    // Inisialisasi lightbox
    lightbox.init();
});

// Membersihkan instance lightbox saat komponen dihancurkan
onUnmounted(() => {
    if (lightbox) {
        // console.log('Destroying PhotoSwipe instance'); // Log untuk debug
        lightbox.destroy();
        lightbox = null;
    }
});

// Watcher untuk memperbarui dataSource jika items berubah (opsional, jika data bisa berubah dinamis)
watch(() => props.items, (newItems) => {
    if (lightbox) {
        // console.log('PhotoSwipe items updated'); // Log untuk debug
        lightbox.options.dataSource = newItems.map(item => ({
             src: `/storage/${item.file_path}`,
             width: item.width || 1200,
             height: item.height || 800,
             id: item.id,
             alt: item.caption || 'Gallery image'
        }));
        // Mungkin perlu me-refresh instance jika API PhotoSwipe mendukungnya
        // lightbox.refresh(); // Cek dokumentasi PhotoSwipe jika perlu
    }
}, { deep: true }); // Periksa perubahan dalam array

</script>

<template>
    <div :id="galleryID" ref="galleryElement" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        <a
            v-for="(item) in items"
            :key="item.id"
            :href="`/storage/${item.file_path}`"
            :data-pswp-src="`/storage/${item.file_path}`"  
            :data-pswp-width="item.width || 1200"
            :data-pswp-height="item.height || 800"
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
            <div v-if="item.caption || item.hits > 0" class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white text-xs p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <span v-if="item.caption">{{ item.caption }}</span>
            </div>
        </a>
    </div>
</template>

<style>
/* Impor styling default dari Tom Select sudah dihapus karena tidak relevan */
/* Styling PhotoSwipe diimpor di <script setup> */

/* Styling tambahan jika diperlukan */
.pswp img {
    max-width: none; /* Override styling default jika ada konflik */
    object-fit: contain;
}
</style>