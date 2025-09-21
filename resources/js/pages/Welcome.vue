<script setup>
import { ref, onMounted} from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import Navigation from '@/Components/Navigation.vue'
import Footer from '@/Components/Footer.vue'

const { props } = usePage()
const articles = props.articles || []
const user = props.auth?.user

defineProps({
  articles: Array
})

onMounted(() => {
  setTimeout(() => {
    isVisible.value = true
  }, 200) // delay biar efek masuk
})

</script>

    <template>
      <Navigation :user="user" :roles="props.auth?.roles || []" />
        <div class="bg-gradient-to-b from-zinc-950 to-red-900">
      <section class="min-h-screen py-16">
        <div class="grid grid-cols-2">
        <div class="text-left px-16 pt-24 pb-6">
          <h1 class="text-4xl font-extrabold mb-2">Selamat Datang di Blog Kami!</h1>
          <p class="text-lg text-white/90 mb-6">Temukan artikel menarik dan terbaru seputar berbagai topik.</p>
          <div class="inline-block">
            <Link
              href="/articles"
              class="px-6 py-3 bg-red-600 text-white font-semibold rounded hover:bg-zinc-900 transition duration-300 hover:text-red-600 transition duration-300"
            >
              Jelajahi Artikel
            </Link>
          </div>
          <div class="mt-6 text-sm text-white/70">
            <p>Ingin berbagi pengetahuan? <Link href="/register" class="text-indigo-400 hover:underline">Daftar sekarang</Link> dan mulai menulis!</p>
          </div>
          <div class="mt-10 text-sm text-white/70">
            <p>Butuh bantuan? Kunjungi <a href="/help" class="text-indigo-400 hover:underline">Pusat Bantuan</a> kami.</p>
          </div>
          <div class="mt-10 text-sm text-white/70">
            <p>Ikuti kami di media sosial untuk update terbaru!</p>
            <div class="flex space-x-4 mt-2">
              <a href="https://twitter.com" target="_blank" class="hover:text-indigo-400 transition duration-300">Twitter</a>
              <a href="https://facebook.com" target="_blank" class="hover:text-indigo-400 transition duration-300">Facebook</a>
              <a href="https://instagram.com" target="_blank" class="hover:text-indigo-400 transition duration-300">Instagram</a>
        </div>
      </div>
      </div>
      <div class="flex justify-center items-center animate-pulse">
                  <img 
      src="img/favicon.png"
      alt="Emergency" 
      class="w-60 min-h-auto object-square"

    /></div>
      </div>


      </section>


    <!-- Main Content -->
    <main class="pt-20 px-16 grid grid-cols-1 md:grid-cols-2 gap-4 p-6 min-h-screen">
      <!-- Artikel Utama -->
      <transition-group
        name="fade"
        tag="div"
        class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-12"
      >
        <div
          v-for="article in articles"
          :key="article.id"
          class="p-6 bg-zinc-800 rounded-xl shadow overflow-hidden hover:shadow-lg hover:scale-[1.02] transition transform duration-300"
        >
          <img 
            v-if="article.cover_image" 
            :src="`/storage/${article.cover_image}`" 
            alt="Cover Image" 
            class="w-full aspect-video object-cover rounded-lg mb-3"
          />

          <h3 class="text-lg font-bold">{{ article.title }}</h3>
          <p class="text-sm text-white mb-2">
            Ditulis oleh: {{ article.user?.name ?? 'Anonim' }}
          </p>
          <p class="mt-2 line-clamp-3 text-white" v-html="article.body"></p>
          <Link
            :href="route('articles.show', article.id)"
            class="mt-3 inline-block text-indigo-600 hover:underline text-sm dark:text-indigo-400"
          >
            Baca Selengkapnya →
          </Link>
        </div>
      </transition-group>
    </main>
    <Footer>]</Footer></div>
</template>

<style>
/* Fade in animation */
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
