<script setup>
import { Link } from '@inertiajs/vue3'; // Import Inertia Link
</script>

<template>
    <footer class="bg-background dark:bg-background border-t border-border-light dark:border-border-dark text-foreground-light dark:text-foreground-dark py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div class="flex flex-col items-center md:items-start">
                    <Link href="/">
                        <img class="h-16 w-auto" src="/img/favicon.png" alt="NERV Logo">
                    </Link>
                    <p class="mt-4 text-sm text-muted-light dark:text-muted-dark text-center md:text-left hover:underline hover:text-(--hover) animate-pulse transition duration-200">
                        © {{ new Date().getFullYear() }} NERV. All rights reserved.
                    </p>
                </div>

                <div class="text-center md:text-left">
                    <h3 class="text-sm font-semibold text-primary dark:text-primary hover:text-muted-foreground dark:hover:text-(--hover) transition-colors duration-200 uppercase tracking-wider">
                        Navigation
                    </h3>
                    <ul class="mt-4 space-y-3 text-sm">
                        <li>
                            <Link :href="route('home')" class="text-primary dark:text-primary hover:text-muted-foreground dark:hover:text-(--hover) transition-colors duration-200">Home</Link>
                        </li>
                        <li>
                            <Link href="/news" class="text-primary dark:text-primary hover:text-muted-foreground dark:hover:text-(--hover) transition-colors duration-200">News</Link>
                        </li>
                        <li>
                            <Link href="/category" class="text-primary dark:text-primary hover:text-muted-foreground dark:hover:text-(--hover) transition-colors duration-200">Category</Link>
                        </li>
                        <li>
                            <Link :href="route('gallery.index')" class="text-primary dark:text-primary hover:text-muted-foreground dark:hover:text-(--hover) transition-colors duration-200">Gallery</Link>
                        </li>
                         </ul>
                </div>

                <div class="text-center md:text-left">
                    <h3 class="text-sm font-semibold text-primary dark:text-primary hover:text-muted-foreground dark:hover:text-(--hover) transition-colors duration-200 uppercase tracking-wider">
                        Follow Us
                    </h3>
                    <div class="mt-4 flex justify-center md:justify-start space-x-5 text-muted-light dark:text-muted-dark text-xl">
                        <a href="https://facebook.com" target="_blank" class="hover:text-primary transition-colors duration-200" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i> </a>
                        <a href="https://twitter.com" target="_blank" class="hover:text-primary transition-colors duration-200" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://instagram.com" target="_blank" class="hover:text-primary transition-colors duration-200" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://github.com" target="_blank" class="hover:text-primary transition-colors duration-200" aria-label="GitHub">
                            <i class="fab fa-github"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<style scoped>
/* Styling spesifik footer bisa ditambahkan di sini jika perlu */
</style>