<script setup>
import { usePage } from '@inertiajs/vue3'
const { props } = usePage()
const article = props.article
</script>

<template>
  <div class="grid grid-cols-12 gap-6 p-6">
    <!-- Main Content -->
    <div class="col-span-8">
      <!-- Category Badge -->
      <!-- <span class="bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded">
        {{ article.category?.name || 'ULASAN' }}
      </span> --> 

      <!-- Title -->
      <h1 class="text-3xl font-bold mt-3 mb-2 leading-tight">
        {{ article.title }}
      </h1>

      <!-- Meta Info -->
      <div class="text-gray-500 text-sm flex items-center gap-2 mb-4">
        <img 
          v-if="article.user.avatar" 
          :src="article.user.avatar" 
          class="w-6 h-6 rounded-full"
        />
        <span class="font-medium text-gray-700">{{ article.user.name }}</span>
        <span>· {{ new Date(article.created_at).toLocaleString() }}</span>
        <span>· {{ article.hits }} Hits</span>
        <span v-if="article.status === 'approved'" class="text-green-600 font-semibold">
          ✔ Approved
        </span>
      </div>

      <!-- Cover Image -->
      <img 
        v-if="article.cover" 
        :src="`/storage/${article.cover}`" 
        alt="Cover"
        class="w-full rounded-lg shadow mb-6"
      />

      <!-- Content -->
      <div class="prose max-w-none" v-html="article.content"></div>

      <!-- Tags -->
      <div class="mt-6 flex flex-wrap gap-2">
        <span 
          v-for="(tag, i) in article.tags || []" 
          :key="i"
          class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded"
        >
          {{ tag }}
        </span>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="col-span-4">
      <h3 class="text-lg font-bold mb-4">Terpopuler</h3>
      <div 
        v-for="pop in props.popularArticles || []" 
        :key="pop.id" 
        class="mb-4 flex gap-3 items-center"
      >
        <img 
          v-if="pop.cover" 
          :src="`/storage/${pop.cover}`" 
          class="w-20 h-14 rounded object-cover"
        />
        <div>
          <a :href="`/articles/${pop.slug}`" class="font-semibold hover:underline">
            {{ pop.title }}
          </a>
          <p class="text-xs text-gray-500">{{ pop.user.name }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
