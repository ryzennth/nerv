<script setup>
import { ref, createApp} from 'vue'
import { useForm, Head } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import InputError from '@/components/InputError.vue'
import AuthBase from '@/layouts/AuthLayout.vue'
import InputText from 'primevue/inputtext'
import { LoaderCircle } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import { QuillEditor } from '@vueup/vue-quill'

const form = useForm({
  title: '',
  content: '',
  cover: null,
})

const submit = () => {
  form.post(route('articles.store'))
}

const app = createApp()
app.component('QuillEditor', QuillEditor)

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
    <div>
        <Head title="Create Article" />

        <form @submit.prevent="submit" class="flex flex-col gap-6 text-black dark:text-white">
          <div class="grid gap-6">
            <div class="grid gap-2">
              <Label for="title">Title</Label>
              <InputText id="title" v-model="form.title" placeholder="Enter article title"
                class="w-full rounded-md bg-transparent border border-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500" />
              <InputError :message="form.errors.title" />
            </div>
                <input
                  type="file"
                  accept="image/*"
                  @change="handleCoverChange"
                />
                <!-- Preview -->
            <div v-if="preview" class="mt-2">
              <p class="text-sm text-gray-500">Preview:</p>
              <img
                :src="preview"
                alt="Cover Preview"
                class="rounded-lg border shadow w-80"
              />
            </div>



              <Label for="content">Content</Label>

              <QuillEditor
                v-model:content="form.content"
                theme="snow"
                contentType="html"
                toolbar="full"
                class="h-80 bg-white text-black dark:bg-zinc-800 text-white"
              />
              <InputError :message="form.errors.content" />


            <Button type="submit"
              class="mt-4 w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 rounded-lg"
              :disabled="form.processing">
              <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
              Submit
            </Button>
          </div>
        </form>
    </div>

    <div class="hidden lg:block relative">
      <img src="/images/write.jpg" alt="background" class="absolute inset-0 w-full h-full object-cover" />
      <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-transparent"></div>
    </div>
  </div>
  </AppLayout>
</template>
