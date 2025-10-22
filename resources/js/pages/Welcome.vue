<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import Navigation from '@/components/Navigation.vue';
import Footer from '@/components/Footer.vue';
import { computed } from 'vue'; // Import computed jika belum

// Mengambil props dari controller dengan lebih aman
const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);
const roles = computed(() => page.props.auth?.roles ?? []);
const articles = computed(() => page.props.articles ?? []);
const popular = computed(() => page.props.popular ?? []);
const featuredArticle = computed(() => page.props.featuredArticle ?? null);

// Helper function untuk excerpt
const createExcerpt = (htmlContent, length = 100) => {
    if (!htmlContent) return '';
    const textContent = htmlContent.replace(/<[^>]*>?/gm, '');
    return textContent.length > length ? textContent.substring(0, length) + '...' : textContent;
};
</script>

<template>
    <Head title="Welcome" />

    <div class="bg-background-light dark:bg-background-dark text-foreground-light dark:text-foreground-dark min-h-screen flex flex-col">
        <Navigation :user="user" :roles="roles" />

        <section class="min-h-screen flex-grow flex items-center py-24 sm:py-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <div class="text-center lg:text-left">
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight mb-4">
                            Selamat Datang di <span class="text-(--hover) animate-pulse">NERV</span> Blog
                        </h1>
                        <p class="text-lg sm:text-xl text-muted-light dark:text-muted-dark mb-8 max-w-xl mx-auto lg:mx-0">
                            Temukan wawasan terbaru, tutorial mendalam, dan berita menarik seputar teknologi.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <Link
                                href="#main-content"
                                class="inline-block px-8 py-3 bg-primary hover:bg-(--hover) hover:text-primary text-primary-foreground font-semibold rounded-md shadow-lg transform hover:-translate-y-0.5 transition duration-300 ease-in-out"
                            >
                                Jelajahi Artikel
                            </Link>
                            <Link
                                v-if="!user"
                                href="/register"
                                class="inline-block px-8 py-3 bg-primary hover:bg-primary-dark text-primary-foreground font-semibold rounded-md shadow-lg transform hover:-translate-y-0.5 transition duration-300 ease-in-out"
                            >
                                Daftar Sekarang
                            </Link>
                        </div>
                        <div v-if="!user" class="mt-6 text-sm text-muted-light dark:text-muted-dark">
                             Sudah punya akun? <Link href="/login" class="text-primary hover:underline font-medium">Login di sini</Link>.
                         </div>
                    </div>

                    <div v-if="featuredArticle" class="group relative rounded-xl overflow-hidden shadow-2xl border border-border-light dark:border-border-dark transform hover:scale-105 transition duration-500 ease-out">
                         <Link :href="route('articles.show', featuredArticle.slug)">
                            <img
                                v-if="featuredArticle.cover"
                                :src="`/storage/${featuredArticle.cover}`"
                                alt="Featured Article Cover"
                                class="w-full h-72 object-cover transition-opacity duration-300"
                            />
                            <div v-else class="w-full h-72 bg-card-light dark:bg-card-dark flex items-center justify-center text-muted-light dark:text-muted-dark">(No Cover)</div>
                             <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                                <span class="bg-primary text-primary-foreground text-xs font-bold px-3 py-1 rounded mb-2 inline-block">
                                    Paling Populer
                                </span>
                                <h3 class="text-xl lg:text-2xl font-bold line-clamp-2 mb-1 group-hover:underline">
                                    {{ featuredArticle.title }}
                                </h3>
                                <p class="text-sm opacity-80">
                                    Oleh {{ featuredArticle.user?.name ?? 'Anonim' }}
                                </p>
                            </div>
                        </Link>
                    </div>
                     <div v-else class="hidden lg:flex items-center justify-center h-72 bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark text-muted-light dark:text-muted-dark italic">
                        Menunggu artikel populer...
                    </div>
                </div>
            </div>
        </section>

        <main class="py-16 lg:py-24 px-4 sm:px-6 lg:px-8 bg-background-light dark:bg-background-dark border-t border-border-light dark:border-border-dark" id="main-content">
             <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

                <section class="lg:col-span-8">
                    <h2 class="text-3xl font-bold mb-8 text-foreground-light dark:text-foreground-dark">Artikel Terbaru</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div
                            v-for="article in articles"
                            :key="article.id"
                            class="bg-card-light dark:bg-card-dark rounded-lg overflow-hidden border border-border-light dark:border-border-dark group transform hover:-translate-y-1 transition duration-300 ease-in-out shadow-sm hover:shadow-xl"
                        >
                            <Link :href="route('articles.show', article.slug)">
                                <img
                                    v-if="article.cover"
                                    :src="`/storage/${article.cover}`"
                                    alt="Cover Image"
                                    class="w-full h-48 object-cover"
                                />
                                <div v-else class="w-full h-48 bg-gray-200 dark:bg-slate-700 flex items-center justify-center text-muted-light dark:text-muted-dark text-sm">No Cover</div>

                                <div class="p-5">
                                    <h3 class="text-lg font-bold text-foreground-light dark:text-foreground-dark mb-2 line-clamp-2 group-hover:text-primary transition">{{ article.title }}</h3>
                                    <p class="text-sm text-muted-light dark:text-muted-dark mb-4 line-clamp-3">
                                        {{ createExcerpt(article.content, 110) }}
                                    </p>
                                    <div class="flex items-center justify-between text-xs text-muted-light dark:text-muted-dark border-t border-border-light dark:border-border-dark pt-3">
                                        <span>{{ article.user?.name ?? 'Anonim' }}</span>
                                        <span>{{ new Date(article.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric'}) }}</span>
                                    </div>
                                </div>
                            </Link>
                        </div>
                         <div v-if="articles.length === 0" class="sm:col-span-2 text-center py-10 text-muted-light dark:text-muted-dark">
                            Belum ada artikel terbaru.
                        </div>
                    </div>
                </section>

                <aside class="lg:col-span-4 h-fit sticky top-24 self-start">
                     <div class="bg-card-light dark:bg-card-dark rounded-lg p-6 border border-border-light dark:border-border-dark shadow-sm">
                        <h2 class="text-xl font-bold mb-5 text-foreground-light dark:text-foreground-dark">Artikel Populer</h2>
                        <ul class="space-y-5">
                            <li
                                v-for="(popArticle, index) in popular"
                                :key="popArticle.id"
                            >
                                <Link :href="route('articles.show', popArticle.slug)" class="flex items-center gap-4 group">
                                    <span class="text-xl font-bold text-muted-light dark:text-muted-dark w-6 text-right">{{ index + 1 }}</span>
                                    <div class="flex-grow">
                                        <h4 class="text-sm font-semibold text-foreground-light dark:text-foreground-dark group-hover:text-primary transition line-clamp-2">{{ popArticle.title }}</h4>
                                        <p class="text-xs text-muted-light dark:text-muted-dark mt-1">{{ popArticle.hits }} hits</p>
                                    </div>
                                </Link>
                            </li>
                            <li v-if="!popular || popular.length === 0">
                                <p class="text-sm text-muted-light dark:text-muted-dark italic">Belum ada artikel populer.</p>
                            </li>
                        </ul>
                    </div>
                </aside>

            </div>
        </main>

        <Footer />
    </div>
</template>

<style scoped>
/* Transisi fade in saat komponen dimuat (opsional) */
/* Ganti .fade jika nama transisi berbeda */
.fade-enter-active {
  transition: opacity 0.8s ease, transform 0.8s ease;
  transition-delay: 0.2s; /* Sedikit delay */
}
.fade-enter-from {
  opacity: 0;
  transform: translateY(20px);
}

/* Ganti transisi default <transition-group> jika perlu */
</style>