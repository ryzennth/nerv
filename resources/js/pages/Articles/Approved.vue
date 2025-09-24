<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const { props } = usePage()
const articles = props.articles.data
const authors  = props.authors
const filters  = props.filters
</script>

<template>
  <AppLayout>
    <div class="p-6">
      <h1 class="text-xl font-bold mb-4">Artikel Disetujui</h1>


       <!-- Filter -->
      <form method="get" class="flex flex-wrap gap-4 mb-4 items-end">
        <!-- Filter Author -->
        <div>
          <label class="block text-sm font-medium mb-1">Penulis</label>
          <select name="author" onchange="this.form.submit()" class="border p-2 rounded">
            <option value="">Semua Penulis</option>
            <option 
              v-for="author in authors" 
              :key="author.id" 
              :value="author.id"
              :selected="filters.author == author.id"
            >
              {{ author.name }}
            </option>
          </select>
        </div>

        <!-- Filter Date From -->
        <div>
          <label class="block text-sm font-medium mb-1">Dari Tanggal</label>
          <input 
            type="date" 
            name="from_date" 
            :value="filters.from_date" 
            class="border p-2 rounded"
            onchange="this.form.submit()"
          />
        </div>

        <!-- Filter Date To -->
        <div>
          <label class="block text-sm font-medium mb-1">Sampai Tanggal</label>
          <input 
            type="date" 
            name="to_date" 
            :value="filters.to_date" 
            class="border p-2 rounded"
            onchange="this.form.submit()"
          />
        </div>
      </form>

      <!-- Table -->
      <table class="w-full border-collapse">
        <thead>
          <tr class="bg-gray-800 text-white">
            <th class="p-2 text-left">Judul</th>
            <th class="p-2">Penulis</th>
            <th class="p-2">Tanggal</th>
          </tr>
        </thead>
        <tbody>
          <tr 
            v-for="article in articles" 
            :key="article.id" 
            class="border-b hover:bg-gray-100"
          >
            <td class="p-2">
              <Link :href="route('articles.show', article.slug)" class="text-blue-600 hover:underline">
                {{ article.title }}
              </Link>
            </td>
            <td class="p-2 text-center">{{ article.user?.name ?? 'Anonim' }}</td>
            <td class="p-2 text-center">{{ new Date(article.created_at).toLocaleDateString() }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>
