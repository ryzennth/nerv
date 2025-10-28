<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

defineProps({
    user: Object, 
    roles: Array, 
});

const scrolled = ref(false);

const handleScroll = () => {
    scrolled.value = window.scrollY > 50; 
};


onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    handleScroll();
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <nav :class="[
        'fixed top-0 w-full flex items-center justify-between px-4 sm:px-6 py-3 z-50 transition-colors duration-300 ease-in-out',
        scrolled
            ? 'bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-md shadow-md border-b border-border-light dark:border-border-dark'
            : 'bg-transparent border-b border-transparent',
        ]">

        <Link href="/" class="flex-shrink-0">
            <img src="/img/favicon.png" alt="Logo NERV" class="h-10 w-auto" />
            </Link>

        <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
            <Link
                href="/"
                class="text-sm font-medium text-primary dark:text-primary hover:text-muted-foreground dark:hover:text-(--hover) transition-colors duration-200"
                :class="{ 'text-primary dark:text-primary': route().current('home') }"
            >
                Home
            </Link>
            <Link
                href="/news"  
                class="text-sm font-medium text-primary dark:text-primary hover:text-muted-foreground dark:hover:text-(--hover) transition-colors duration-200"
                :class="{ 'text-primary dark:text-primary': route().current('news.*') }" 
            >
                News
            </Link>
            <Link
                href="/category" 
                class="text-sm font-medium text-primary dark:text-muted-dark hover:text-muted-foreground dark:hover:text-(--hover) transition-colors duration-200"
                :class="{ 'text-primary dark:text-primary': route().current('category.*') }" 
            >
                Category
            </Link>
            <Link
                :href="route('gallery.index')" 
                class="text-sm font-medium text-primary dark:text-muted-dark hover:text-muted-foreground dark:hover:text-(--hover) transition-colors duration-200"
                :class="{ 'text-primary dark:text-primary': route().current('gallery.*') }" 
            >
                Gallery
            </Link>
            <Link
                v-if="user && roles.includes('Super Admin', 'admin')" 
                :href="route('dashboard')"
                class="text-sm font-medium text-primary dark:text-muted-dark hover:text-muted-foreground dark:hover:text-(--hover) transition-colors duration-200"
                :class="{ 'text-primary dark:text-primary': route().current('dashboard') }" 
             >
                 Dashboard
             </Link>
        </div>

        <div class="flex items-center">
            <template v-if="user">
                <div class="relative ml-3">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                type="button"
                                class="flex items-center text-sm font-medium text-foreground-light dark:text-foreground-dark hover:text-muted-light dark:hover:text-muted-dark focus:outline-none transition duration-150 ease-in-out"
                            >
                                <img
                                    :src="user.avatar ? `/storage/${user.avatar}` : `https://ui-avatars.com/api/?name=${user.name}&background=random&color=fff`"
                                    alt="Profile Photo"
                                    class="h-8 w-8 rounded-full object-cover mr-2"
                                />
                                <span class="hidden sm:inline">{{ user.name }}</span>
                                <svg class="ml-1 h-4 w-4 fill-current opacity-70" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <div class="py-1 bg-card-light dark:bg-card-dark rounded-md shadow-lg border border-border-light dark:border-border-dark">
                                <DropdownLink :href="route('users.show', user.username)">
                                    Profile
                                </DropdownLink>
                                <DropdownLink :href="route('profile.edit')">
                                    Settings
                                </DropdownLink>
                                <hr class="border-border-light dark:border-border-dark my-1 mx-2">
                                <DropdownLink :href="route('logout')" method="post" as="button" class="text-red-600 dark:text-red-400 w-full text-left">
                                    Log Out
                                </DropdownLink>
                            </div>
                        </template>
                    </Dropdown>
                </div>
            </template>

            <template v-else>
                <div class="flex items-center space-x-3">
                    <Link
                        href="/login"
                        class="px-4 py-2 text-sm font-medium bg-primary hover:bg-muted hover:text-primary dark:hover:bg-(--hover) dark:hover:text-primary text-primary-foreground rounded-md shadow transition duration-200"
                    >
                        Login
                    </Link>
                    <Link
                        href="/register"
                        class="px-4 py-2 text-sm font-medium bg-primary hover:bg-muted hover:text-primary dark:hover:bg-(--hover) dark:hover:text-primary text-primary-foreground rounded-md shadow transition duration-200"
                    >
                        Register
                    </Link>
                </div>
            </template>

             </div>
    </nav>
</template>