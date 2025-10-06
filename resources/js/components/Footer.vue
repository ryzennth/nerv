<script></script><script setup>
// tidak perlu script khusus, tapi bisa tambahkan reactive data jika mau lebih dinamis
</script>

<style scoped>
/* Jika ingin styling tambahan khusus footer */
</style><template>
  <footer class="bg-transparent shadow text-zinc-800 py-8 mt-12">
    <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <!-- Brand / Logo -->
        <div>
          <img class="h-20" src="/img/favicon.png" alt="logo">
          <p class="mt-2 text-sm text-zinc-600 hover:text-red-600">
            © {{ new Date().getFullYear() }} NERV. All rights reserved.
          </p>
        </div>

        <!-- Navigation -->
        <div>
          <h3 class="text-sm font-semibold text-zinc-700 uppercase tracking-wider">
            Navigation
          </h3>
          <ul class="mt-4 space-y-2">
            <li>
              <RouterLink to="/" class="hover:text-red-600 transition">Home</RouterLink>
            </li>
            <li>
              <RouterLink to="/about" class="hover:text-red-600 transition">About</RouterLink>
            </li>
            <li>
              <RouterLink to="/contact" class="hover:text-red-600 transition">Contact</RouterLink>
            </li>
            <li>
              <RouterLink to="/blog" class="hover:text-red-600 transition">Blog</RouterLink>
            </li>
          </ul>
        </div>

        <!-- Social Media -->
        <div>
          <h3 class="text-sm font-semibold text-zinc-700 uppercase tracking-wider">
            Follow Us
          </h3>
          <div class="mt-4 flex space-x-4">
            <a href="https://facebook.com" target="_blank" class="hover:text-white transition">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://twitter.com" target="_blank" class="hover:text-white transition">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="https://instagram.com" target="_blank" class="hover:text-white transition">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="https://github.com" target="_blank" class="hover:text-white transition">
              <i class="fab fa-github"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>