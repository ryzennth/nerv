<script setup>
import { ref, createApp } from 'vue'
import { useForm, Head } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import InputError from '@/components/InputError.vue'
import InputText from 'primevue/inputtext'
import { LoaderCircle } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import { QuillEditor } from '@vueup/vue-quill'

const props = defineProps({
  categories: Array,
  tags: Array
})

const form = useForm({
  title: '',
  content: '',
  cover: null,
  category_id: '',   // kategori tunggal
  tags: []           // bisa banyak
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
    <div class="text-black dark:text-white px-16 py-8">
      <Head title="Create Article" />

      <form @submit.prevent="submit" class="flex flex-col gap-6">
        <!-- Title -->
        <div>
          <Label for="title">Title</Label>
          <InputText
            id="title"
            v-model="form.title"
            placeholder="Enter article title"
            class="w-full rounded-md border px-3 py-2"
          />
          <InputError :message="form.errors.title" />
        </div>

        <!-- Cover -->
        <div>
          <Label for="cover">Cover</Label>
          <input type="file" accept="image/*" @change="handleCoverChange" />
          <div v-if="preview" class="mt-2">
            <img :src="preview" alt="Preview" class="rounded-lg border shadow w-80" />
          </div>
        </div>
        <div>
          <p class="text-red-600 hover:text-zinc-100 transition duration-300 animate-pulse">Cover minimal berukuran 700x450 dan maksimal ukuran filenya 5MB</p>
        </div>

        <!-- Content -->
        <div>
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

        <!-- Submit -->
        <Button
          type="submit"
          class="mt-4 w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 rounded-lg"
          :disabled="form.processing"
        >
          <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
          Submit
        </Button>
      </form>
    </div>
  </AppLayout>
</template>
