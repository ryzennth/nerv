<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps<{ articles: any[] }>();

const approve = (id: number) => {
  useForm({}).post(route('articles.approve', id));
};

const reject = (id: number) => {
  useForm({}).post(route('articles.reject', id));
};
</script>

<template>
  <Head title="Moderation" />
<AppLayout>

  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Article Moderation</h1>

    <div v-if="articles.length === 0" class="text-gray-500">
      No pending articles 🎉
    </div>

    <div v-for="article in articles" :key="article.id" class="p-4 bg-white rounded shadow mb-3 dark:bg-zinc-800">
      <h2 class="text-lg font-semibold">{{ article.title }}</h2>
      <p class="text-gray-600" v-html="article.content"></p>

      <div class="mt-3 flex gap-2">
        <button @click="approve(article.id)" class="px-3 py-1 bg-green-600 text-white rounded">Approve</button>
        <button @click="reject(article.id)" class="px-3 py-1 bg-red-600 text-white rounded">Reject</button>
      </div>
    </div>
  </div>
</AppLayout>
</template>
