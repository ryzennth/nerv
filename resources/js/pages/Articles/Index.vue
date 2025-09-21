<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
const { props } = usePage();
const articles = props.articles.data;
</script>

<template>
  <AppLayout>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Articles</h1>
    <Link href="/articles/create" class="px-4 py-2 bg-blue-500 text-white rounded">New Article</Link>

    <ul class="mt-4">
      <li v-for="a in articles" :key="a.id" class="border p-3 mb-2">
        <Link :href="`/articles/${a.id}`" class="font-semibold">{{ a.title }}</Link>
        <p class="text-sm text-gray-500">By {{ a.user.name }} - {{ a.status }}</p>
      </li>
    </ul>
  </div>
  </AppLayout>
</template>

