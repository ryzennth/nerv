<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import InputError from '@/components/InputError.vue';
import { ref } from 'vue';

const props = defineProps({
    album: Object, // Data album (termasuk photos yang sudah di-load)
});

// Form untuk upload foto baru
const form = useForm({
    photos: [], // Akan menampung array file
    captions: [], // Array caption (opsional)
});

// State untuk preview gambar yang dipilih
const previews = ref([]);

// Fungsi untuk menangani pemilihan file
const handleFileChange = (event) => {
    form.photos = Array.from(event.target.files); // Simpan file ke form
    previews.value = []; // Reset preview
    form.captions = []; // Reset caption

    // Buat URL preview untuk setiap file
    form.photos.forEach((file, index) => {
        previews.value.push(URL.createObjectURL(file));
        form.captions[index] = ''; // Inisialisasi caption kosong
    });
};

// Fungsi untuk submit foto baru
const submitPhotos = () => {
    form.post(route('albums.photos.store', props.album.slug), {
        onSuccess: () => {
            form.reset(); // Kosongkan form setelah berhasil
            previews.value = []; // Kosongkan preview
            // Inertia akan otomatis refresh data album dan photos
        },
    });
};

// Fungsi untuk menghapus foto
const destroyPhoto = (photoId) => {
    if (confirm('Are you sure you want to delete this photo?')) {
        router.delete(route('photos.destroy', photoId), {
            preserveScroll: true, // Jaga posisi scroll
            onSuccess: () => {
                 // Inertia akan otomatis refresh
            }
        });
    }
};
</script>

<template>
    <Head :title="`Manage Photos for ${album.title}`" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Manage Photos: {{ album.title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Upload New Photos</h3>
                        <form @submit.prevent="submitPhotos">
                            <div>
                                <label for="photos" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Select Images</label>
                                <input
                                    id="photos"
                                    type="file"
                                    multiple
                                    accept="image/*"
                                    @change="handleFileChange"
                                    class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.photos" />
                                <InputError class="mt-2" :message="form.errors['photos.0']" /> </div>

                            <div v-if="previews.length > 0" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                <div v-for="(preview, index) in previews" :key="index">
                                    <img :src="preview" class="w-full h-auto aspect-square object-cover rounded-md mb-2">
                                    <input
                                        type="text"
                                        v-model="form.captions[index]"
                                        placeholder="Add a caption (optional)"
                                        class="text-sm block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                                    />
                                    <InputError class="mt-1" :message="form.errors[`captions.${index}`]" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 disabled:opacity-25 transition"
                                    :disabled="form.processing || previews.length === 0"
                                >
                                    Upload Photos
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Existing Photos</h3>
                        <div v-if="album.photos && album.photos.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            <div v-for="photo in album.photos" :key="photo.id" class="relative group">
                                <img :src="`/storage/${photo.file_path}`" :alt="photo.caption || 'Photo'" class="w-full h-auto aspect-square object-cover rounded-md">
                                <button
                                    @click="destroyPhoto(photo.id)"
                                    class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity focus:outline-none"
                                    title="Delete Photo"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                                <p v-if="photo.caption" class="text-xs text-center mt-1 text-gray-600 dark:text-gray-400 truncate">{{ photo.caption }}</p>
                            </div>
                        </div>
                        <p v-else class="text-gray-500">No photos uploaded to this album yet.</p>
                    </div>
                </div>

                 <div class="mt-6">
                    <Link :href="route('albums.index')" class="text-orange-500 hover:underline">&larr; Back to Albums List</Link>
                </div>

            </div>
        </div>
    </AppLayout>
</template>