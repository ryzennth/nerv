<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Swal from 'sweetalert2';

const { props } = usePage();
const articles = props.articles.data;
const pagination = props.articles;

function destroy(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "This action cannot be undone!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!',
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete(`/articles/${id}`, {
        onSuccess: () => {
          Swal.fire('Deleted!', 'Your article has been deleted.', 'success');
        },
      });
    }
  });
}
</script>


<template>
  <AppLayout>
    <div class="p-6">
      <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold">Articles</h1>
        <Link href="/articles/create" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-zinc-900 transition duration-300 hover:text-red-600 transition duration-300">
          + Add Article
        </Link>
      </div>

      <!-- Filter & search -->
      <div class="flex items-center gap-2 mb-4">
        <select v-model="selectedCategory" class="border rounded px-2 py-1">
          <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
        </select>
        <input
          type="text"
          placeholder="Search"
          class="border rounded px-2 py-1 flex-1"
        />
      </div>

      <!-- Table -->
      <div class="bg-white rounded shadow">
        <table class="w-full text-sm text-left border-collapse">
          <thead class="bg-gray-100 text-gray-700 uppercase text-xs dark:bg-zinc-900 text-white">
            <tr>
              <th class="px-4 py-3">Title</th>
              <th class="px-4 py-3">Author</th>
              <th class="px-4 py-3">Status</th>
              <th class="px-4 py-3">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white text-black dark:bg-zinc-700 text-white">
            <tr
              v-for="a in articles"
              :key="a.id"
              class="border-b hover:bg-zinc-50 dark:hover:bg-zinc-600"
            >
              <td class="px-4 py-3 font-medium">
                <Link :href="`/articles/${a.id}`">{{ a.title }}</Link>
              </td>
              <td class="px-4 py-3">{{ a.user.name }}</td>
              <td class="px-4 py-3">
                <span
                  :class="[
                    'px-2 py-1 rounded-full text-xs font-semibold',
                    a.status === 'approved'
                      ? 'bg-green-100 text-green-700'
                      : a.status === 'rejected'
                      ? 'bg-red-100 text-red-700'
                      : 'bg-yellow-100 text-yellow-700'
                  ]"
                >
                  {{ a.status }}
                </span>
              </td>
              <td class="px-4 py-3 flex gap-2">
                <!-- Show -->
                <Link
                  :href="`/articles/${a.slug}`"
                  class="px-2 py-1 text-white bg-green-500 rounded"
                >
                  Show
                </Link>

                <!-- Edit (hanya kalau belum approved) -->
                <Link
                  v-if="a.status !== 'approved'"
                  :href="`/articles/${a.id}/edit`"
                  class="px-2 py-1 text-white bg-blue-500 rounded"
                >
                  Edit
                </Link>

                <!-- Delete -->
                <button
                  @click="destroy(a.id)"
                  class="px-2 py-1 text-white bg-red-500 rounded"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="flex justify-between items-center mt-4 text-sm">
        <div>
          Showing {{ pagination.from }} to {{ pagination.to }} of
          {{ pagination.total }} entries
        </div>
        <div class="flex gap-2">
          <Link
            v-if="pagination.prev_page_url"
            :href="pagination.prev_page_url"
            class="px-3 py-1 border rounded hover:bg-gray-100"
          >
            Prev
          </Link>
          <Link
            v-if="pagination.next_page_url"
            :href="pagination.next_page_url"
            class="px-3 py-1 border rounded hover:bg-gray-100"
          >
            Next
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
