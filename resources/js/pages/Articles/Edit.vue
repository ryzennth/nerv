<script setup>
import { ref } from 'vue'
import { useForm, Head } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import InputError from '@/components/InputError.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import { LoaderCircle } from 'lucide-vue-next'
import { QuillEditor } from '@vueup/vue-quill' // Impor QuillEditor
import '@vueup/vue-quill/dist/vue-quill.snow.css'; // Impor CSS Quill

const props = defineProps({
    article: Object,
    categories: Array,
    tags: Array,
})

const form = useForm({
    _method: 'patch', // Penting untuk update dengan file
    title: props.article.title,
    content: props.article.content,
    cover: null, // Hanya diisi jika ada file baru
    category_id: props.article.category_id,
    tags: props.article.tags.map(tag => tag.id), // Ambil array of IDs
})

const submit = () => {
    // Gunakan POST karena ada file, Laravel akan membacanya sebagai PATCH
    form.post(route('articles.update', props.article.slug))
}

// Logika untuk preview cover baru
const preview = ref(null)
function handleCoverChange(e) {
    const file = e.target.files[0]
    if (file) {
        form.cover = file
        preview.value = URL.createObjectURL(file)
    }
}
</script>

<template>
    <AppLayout>
        <div class="text-black dark:text-white px-16 py-8">
            <Head :title="`Edit Article: ${form.title}`" />

            <form @submit.prevent="submit" class="flex flex-col gap-6">
                <div>
                    <Label for="title">Title</Label>
                    <InputText
                        id="title"
                        v-model="form.title"
                        placeholder="Enter article title"
                        class="w-full rounded-md border px-3 py-2 bg-white dark:bg-zinc-800"
                    />
                    <InputError :message="form.errors.title" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <Label for="category_id">Category</Label>
                        <select
                            id="category_id"
                            v-model="form.category_id"
                            class="w-full rounded-md border px-3 py-2 bg-white dark:bg-zinc-800"
                        >
                            <option value="" disabled>Select a category</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                        <InputError :message="form.errors.category_id" />
                    </div>

                    <div>
                        <Label for="tags">Tags</Label>
                        <select
                            id="tags"
                            v-model="form.tags"
                            multiple
                            class="w-full rounded-md border px-3 py-2 bg-white dark:bg-zinc-800"
                        >
                            <option v-for="tag in tags" :key="tag.id" :value="tag.id">
                                {{ tag.name }}
                            </option>
                        </select>
                        <InputError :message="form.errors.tags" />
                    </div>
                </div>

                <div>
                    <Label for="cover">Change Cover</Label>
                    <input type="file" accept="image/*" @change="handleCoverChange" />
                    <InputError :message="form.errors.cover" />

                    <div v-if="preview" class="mt-4">
                        <p class="text-sm text-gray-500">New cover preview:</p>
                        <img :src="preview" alt="Preview" class="rounded-lg border shadow w-80" />
                    </div>
                    <div v-else-if="article.cover" class="mt-4">
                        <p class="text-sm text-gray-500">Current cover:</p>
                        <img :src="`/storage/${article.cover}`" alt="Current Cover" class="rounded-lg border shadow w-80" />
                    </div>
                </div>

                <div class="mt-4">
                    <Label for="content">Content</Label>
                    <QuillEditor
                        v-model:content="form.content"
                        theme="snow"
                        contentType="html"
                        toolbar="full"
                        class="h-80 bg-white dark:bg-zinc-800"
                    />
                    <InputError :message="form.errors.content" />
                </div>

                <Button
                    type="submit"
                    class="mt-12 w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 rounded-lg"
                    :disabled="form.processing"
                >
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Update Article
                </Button>
            </form>
        </div>
    </AppLayout>
</template>