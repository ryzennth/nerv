<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Swal from 'sweetalert2'

const form = useForm({
  name: '',
  description: ''
})

function submit() {
  form.post(route('categories.store'), {
    onSuccess: () => {
      Swal.fire('Success', 'Category created!', 'success')
      router.get(route('categories.index'))
    },
    onError: () => {
      Swal.fire('Error', 'Something went wrong!', 'error')
    }
  })
}
</script>

<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-4">Create Category</h1>
    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block">Name</label>
        <input v-model="form.name" type="text" class="border rounded w-full p-2" />
        <div v-if="form.errors.name" class="text-red-500">{{ form.errors.name }}</div>
      </div>

      <div>
        <label class="block">Description</label>
        <textarea v-model="form.description" class="border rounded w-full p-2"></textarea>
      </div>

      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
    </form>
  </AppLayout>
</template>
