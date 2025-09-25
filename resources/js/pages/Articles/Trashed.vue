<script setup>
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'

defineProps({
  articles: Object, // paginated dari controller
})

// konfirmasi restore
const restoreArticle = (id) => {
  if (confirm('Yakin mau restore artikel ini?')) {
    router.post(route('articles.restore', id))
  }
}

// konfirmasi force delete
const forceDeleteArticle = (id) => {
  if (confirm('Artikel akan dihapus permanen, lanjutkan?')) {
    router.delete(route('articles.forceDelete', id))
  }
}
</script>

<template>
<AppLayout>
  <div class="min-h-screen px-16 py-10 text-white">
    <h1 class="text-2xl font-bold mb-6">🗑️ Trashed Articles</h1>

    <div v-if="articles.data.length" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div
        v-for="article in articles.data"
        :key="article.id"
        class="p-6 bg-zinc-800 rounded-xl shadow flex flex-col justify-between"
      >
        <div>
          <h3 class="text-lg font-bold mb-2">{{ article.title }}</h3>
          <p class="text-sm text-gray-300 mb-4">Ditulis oleh: {{ article.user?.name ?? 'Anonim' }}</p>
          <p class="text-xs text-gray-400">Dihapus pada: {{ article.deleted_at }}</p>
        </div>

        <div class="mt-4 flex space-x-3">
          <button
            @click="restoreArticle(article.id)"
            class="px-3 py-1 bg-green-600 rounded hover:bg-green-700 transition text-sm"
          >
            Restore
          </button>
          <button
            @click="forceDeleteArticle(article.id)"
            class="px-3 py-1 bg-red-600 rounded hover:bg-red-700 transition text-sm"
          >
            Hapus Permanen
          </button>
        </div>
      </div>
    </div>

    <div v-else class="text-center text-gray-400">
      Belum ada artikel yang dihapus.
    </div>

    <!-- Pagination -->
    <div class="mt-8 flex justify-center">
      <div class="flex space-x-2">
        <Link
          v-for="link in articles.links"
          :key="link.label"
          :href="link.url || '#'"
          v-html="link.label"
          class="px-3 py-1 rounded"
          :class="{
            'bg-red-600 text-white': link.active,
            'text-gray-300 hover:bg-zinc-700': !link.active
          }"
        />
      </div>
    </div>
  </div>
  </AppLayout>
</template>
