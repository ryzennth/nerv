<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Swal from 'sweetalert2'

const form = useForm({
  name: ''
})

function submit() {
  form.post(route('tags.store'), {
    onSuccess: () => {
      Swal.fire('Success', 'Tag created!', 'success')
      router.get(route('tags.index'))
    },
    onError: () => {
      Swal.fire('Error', 'Something went wrong!', 'error')
    }
  })
}
</script>

<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-4">Create Tag</h1>
    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block">Name</label>
        <input v-model="form.name" type="text" class="border rounded w-full p-2" />
        <div v-if="form.errors.name" class="text-red-500">{{ form.errors.name }}</div>
      </div>

      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
    </form>
  </AppLayout>
</template>
