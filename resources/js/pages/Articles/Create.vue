<script setup>
import { ref } from 'vue'
import { useForm, Head } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import InputError from '@/components/InputError.vue'
import { LoaderCircle } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import { QuillEditor } from '@vueup/vue-quill'
import TomSelect from '@/components/TomSelect.vue'

const props = defineProps({
    categories: Array,
    tags: Array
})

const form = useForm({
    title: '',
    content: '',
    cover: null,
    category_id: '',
    tags: []
})

const submit = () => {
    form.post(route('articles.store'))
}

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
        <div class="text-black dark:text-white px-4 sm:px-8 md:px-16 py-8">
            <Head title="Create Article" />

            <form @submit.prevent="submit" class="flex flex-col gap-6">

                <div>
                    <Label for="title">Title</Label>
                    <input
                        id="title"
                        v-model="form.title"
                        type="text"
                        placeholder="Enter article title"
                        class="mt-1 w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-zinc-800 px-3 py-2"
                    />
                    <InputError class="mt-1" :message="form.errors.title" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <Label for="category_id">Category</Label>
                        <select
                            id="category_id"
                            v-model="form.category_id"
                            class="mt-1 w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-zinc-800 px-3 py-2"
                        >
                            <option value="" disabled>Select a category</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                        <InputError class="mt-1" :message="form.errors.category_id" />
                    </div>
                    <div>
                    <Label for="tags">Tags</Label>
                        <TomSelect
                            id="tags"
                            v-model="form.tags"
                            :options="tags"
                            multiple
                            class="mt-1 w-full"
                        />
                        <InputError class="mt-1" :message="form.errors.tags" />
                    </div>
                </div>

                <div>
                    <Label for="cover">Cover</Label>
                    <input class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" type="file" accept="image/*" @change="handleCoverChange" />
                    <div v-if="preview" class="mt-4">
                        <img :src="preview" alt="Preview" class="rounded-lg border shadow w-80" />
                    </div>
                    <InputError class="mt-1" :message="form.errors.cover" />
                    <p class="mt-2 text-sm text-red-600">Cover minimal berukuran 700x450 dan maksimal ukuran filenya 5MB.</p>
                </div>

                <div class="mt-4">
                    <Label for="content" class="mb-1 block">Content</Label>
                    <QuillEditor
                        v-model:content="form.content"
                        theme="snow"
                        contentType="html"
                        toolbar="full"
                        class="h-80 bg-white dark:bg-zinc-800"
                    />
                    <InputError class="mt-1" :message="form.errors.content" />
                </div>

                <Button
                    type="submit"
                    class="mt-8 w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 rounded-lg"
                    :disabled="form.processing"
                >
                    <LoaderCircle v-if="form.processing" class="inline-block mr-2 h-4 w-4 animate-spin" />
                    Submit
                </Button>

            </form>
        </div>
    </AppLayout>
</template>
