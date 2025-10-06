<script setup>
import { ref, onMounted, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import Navigation from '@/Components/Navigation.vue'
import Footer from '@/Components/Footer.vue'

const page = usePage()

const articles = computed(() => page.props.articles ?? [])
const popular  = computed(() => page.props.popular ?? [])

const user = computed(() => page.props.auth?.user ?? null)
const roles = computed(() => page.props.auth?.roles ?? [])

const isVisible = ref(false)

onMounted(() => {
  setTimeout(() => {
    isVisible.value = true
  }, 200) 
})

</script>

<template>
  <Navigation :user="user" :roles="roles" />
  <section class="min-h-screen py-80 block md:hidden">
      <div class="grid gap-8 items-center justify-center">
    <div class="relative group">
      <div class="absolute -inset-0.5 bg-gradient-to-b from-purple-500 to-lime-700 rounded-lg blur opacity-75 group-hover:opacity-100 transition duration-300"></div>
      <button class="relative px-7 py-4 bg-red-950 rounded-lg leading-non flex items-center divide-x divide-zinc-700">
        <span class="pr-6 text-white">NERV News</span>
        <span class="pl-6 text-white group-hover:text-zinc-500 transition duration-300"><a href="#main">See what's new &rarr;</a></span>
      </button>
    </div>
  </div>
  </section>
  <div class="bg-gradient-to-b from-zinc-950 to-red-900">
    <!-- Hero -->
    <section class="min-h-screen pt-25">
      <div class="md:grid grid-cols-2">
        <!-- Hero Text -->
        <div class="text-left px-16 pt-24 pb-6">
          <h1 class="text-4xl font-extrabold mb-2">Selamat Datang di Blog Kami!</h1>
          <p class="text-lg text-white/90 mb-6">Temukan artikel menarik dan terbaru seputar berbagai topik.</p>

          <div class="inline-block">
            <Link
              href="/articles"
              class="px-6 py-3 bg-red-600 text-white font-semibold rounded hover:bg-zinc-900 transition duration-300 hover:text-red-600"
            >
              Jelajahi Artikel
            </Link>
          </div>

          <div class="mt-6 text-sm text-white/70">
            <p>Ingin berbagi pengetahuan? <Link href="/register" class="text-indigo-400 hover:underline">Daftar sekarang</Link> dan mulai menulis!</p>
          </div>
          <div class="mt-10 text-sm text-white/70">
            <p>Butuh bantuan? Kunjungi <a href="/help" class="text-indigo-400 hover:underline">Pusat Bantuan</a>.</p>
          </div>
        </div>

        <!-- Hero Image -->
        <div class="flex justify-center items-center animate-pulse">
          <img src="img/favicon.png" alt="Emergency" class="w-80 object-contain hidden md:block" />
        </div>
      </div>
    </section>



   <!-- Main Content -->
<main class="pt-60 px-16 grid grid-cols-1 md:grid-cols-3 gap-6 min-h-screen" id="main">
  <!-- Artikel Utama (span 2 kolom) -->
  <section class="md:col-span-2">
    <transition-group 
      name="fade" 
      tag="div" 
      class="grid grid-cols-1 sm:grid-cols-2 gap-12 w-full"
    >
      <div
        v-for="article in articles"
        :key="article.id"
        class="p-6 bg-zinc-800 rounded-xl shadow overflow-hidden hover:shadow-lg transition transform duration-300"
      >
        <img
          v-if="article.cover"
          :src="`/storage/${article.cover}`"
          alt="Cover Image"
          class="w-full aspect-video object-cover rounded-lg mb-3"
        />

        <h3 class="text-lg font-bold text-white">{{ article.title }}</h3>
        <p class="text-sm text-white/70 mb-2">
          Ditulis oleh: {{ article.user?.name ?? 'Anonim' }}
        </p>
        <p class="mt-2 line-clamp-3 text-white" v-html="article.content"></p>
        <Link
          :href="route('articles.show', article.slug)"
          class="mt-3 inline-block text-indigo-400 hover:underline text-sm"
        >
          Baca Selengkapnya →
        </Link>
      </div>
    </transition-group>
  </section>

  <!-- Sidebar Artikel Populer (sticky) -->
  <aside class="bg-zinc-900 rounded-xl p-4 shadow h-fit sticky top-24 self-start">
    <h2 class="text-lg font-bold mb-4 text-white">Artikel Populer</h2>
    <ul class="space-y-3">
      <li
        v-for="article in popular"
        :key="article.id"
        class="border-b border-white/10 pb-2 last:border-none"
      >
        <div>
          <img
            v-if="article.cover"
            :src="`/storage/${article.cover}`"
            alt="Cover Image"
            class="w-full aspect-video object-cover rounded-lg mb-3"
          />
          <Link
            :href="route('articles.show', article.slug)"
            class="text-sm text-white hover:text-indigo-400 transition"
          >
            {{ article.title }}
          </Link>
          <p class="text-xs text-white/60">{{ article.hits }} hits</p>
        </div>
      </li>
    </ul>
  </aside>
</main>


    <Footer />
  </div>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.6s ease, transform 0.6s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(15px);
}
</style>
