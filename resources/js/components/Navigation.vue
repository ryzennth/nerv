<template>
  <nav :class="[
      'fixed top-0 w-full flex items-center justify-between px-6 py-2 z-50 transition-all duration-1000',
      scrolled
        ? 'bg-zinc-900/70 backdrop-blur-md shadow'
        : 'bg-transparent',
    ]">
    <img src="/img/favicon.png" alt="Logo" class="h-10 w-auto" />

    <div class="flex space-x-8 text-white hidden md:block">
      <Link href="/" class="hover:underline transition duration-300">Home</Link>
      <Link href="/news" class="hover:underline transition duration-300">News</Link>
      <Link href="/category" class="hover:underline transition duration-300">Category</Link>
    </div>

    <div>
      <template v-if="user">
        <div class="hidden sm:ms-6 sm:flex sm:items-center">
          <div class="relative ms-3">
            <Dropdown align="right" width="48">
              <template #trigger>
                <span class="inline-flex rounded-md">
                  <button
                    type="button"
                    class="inline-flex items-center rounded-md border border-transparent  px-3 py-2 text-sm font-medium leading-4 text-white hover:text-gray-400 transition focus:outline-none"
                  >
                    <div class="flex items-center space-x-2">
                      <img
                        :src="user.avatar ? `/storage/${user.avatar}` : '/default-profile.png'"
                        alt="Profile Photo"
                        class="h-8 w-8 rounded-full object-cover border border-transparent"
                      />
                      <div class="text-left">
                        <div class="flex items-center gap-2 hidden md:block">
                          <span>{{ user.name }}</span>
                        </div>
                        <div class="text-xs text-white hidden md:block">{{ user.username }}</div>
                      </div>
                    </div>
                    <svg
                      class="ml-2 h-4 w-4"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                      />
                    </svg>
                  </button>
                </span>
              </template>

              <template #content>
                <DropdownLink :href="route('users.show', user.username)">Profile</DropdownLink>
                <DropdownLink :href="route('profile.edit')">Settings</DropdownLink>
                <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
              </template>
            </Dropdown>
          </div>
        </div>
      </template>

      <template v-else>
        <div class="flex space-x-3">
          <Link
            href="/login"
            class="px-4 py-2 bg-red-600 text-white font-semibold rounded hover:bg-zinc-800 transition duration-300 hover:text-red-600 transition duration-300"
          >
            Login
          </Link>
          <Link
            href="/register"
            class="px-4 py-2 bg-red-600 text-white font-semibold rounded hover:bg-zinc-800 transition duration-300 hover:text-red-600 transition duration-300"
          >
            Register
          </Link>
        </div>
      </template>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'

defineProps({
  user: Object,
  roles: Array
})

const scrolled = ref(false)

const handleScroll = () => {
  scrolled.value = window.scrollY > 0
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>
