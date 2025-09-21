<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps({
  article: Object
})
</script>

<template>
  <AppLayout>
  <div class="min-h-screen bg-black text-white p-8 sm:p-16 lg:px-48">
    <Head :title="article.title" />

    <h1 class="text-3xl font-bold mb-4">{{ article.title }}</h1>
    <p class="text-gray-400 text-sm mb-6">By {{ article.user.name }} · {{ article.created_at }}</p>

    <div class="prose prose-invert max-w-none">
      <p v-html="article.content"></p>
    </div>

    <div class="mt-10">
      <a href="/articles" class="text-orange-500 hover:underline">&larr; Back to Articles</a>
    </div>
  </div>
  </AppLayout>
</template>
