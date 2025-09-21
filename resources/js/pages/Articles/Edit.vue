<script setup>
import { useForm, Head } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import InputError from '@/components/InputError.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import { LoaderCircle } from 'lucide-vue-next'

const props = defineProps({
  article: Object
})

const form = useForm({
  title: props.article.title,
  content: props.article.content
})

const submit = () => {
  form.put(route('articles.update', props.article.id))
}
</script>

<template>
  <AppLayout>
  <div class="text-white">
    <div>

        <Head title="Edit Article" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
          <div class="grid gap-6">
            <div class="grid gap-2">
              <Label for="title">Title</Label>
              <InputText id="title" v-model="form.title" placeholder="Enter article title"
                class="w-full rounded-md bg-transparent border border-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500" />
              <InputError :message="form.errors.title" />
            </div>

            <div class="grid gap-2">
              <Label for="content">Content</Label>
              <Textarea id="content" v-model="form.content" rows="8" placeholder="Update your content here..."
                class="w-full rounded-md bg-transparent border border-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500" />
              <InputError :message="form.errors.content" />
            </div>

            <Button type="submit"
              class="mt-4 w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 rounded-lg"
              :disabled="form.processing">
              <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
              Update
            </Button>
          </div>
        </form>
    </div>

    <div class="hidden lg:block relative">
      <img src="/images/edit.jpg" alt="background" class="absolute inset-0 w-full h-full object-cover" />
      <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-transparent"></div>
    </div>
  </div>
  </AppLayout>
</template>
