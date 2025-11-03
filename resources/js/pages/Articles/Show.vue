<script setup>
import { Head, Link, usePage, router, useForm } from '@inertiajs/vue3';
import Navigation from '@/components/Navigation.vue';
import Footer from '@/components/Footer.vue';
import { defineProps, computed } from 'vue';
import { Heart } from 'lucide-vue-next';


const props = defineProps({
    article: Object,
    popularArticles: Array,
    auth: Object, // Menerima data auth untuk Navigation
    likes_count: Number,
    is_liked_by_user: Boolean,
});

// Format tanggal menjadi lebih ramah dibaca (e.g., "13 Oktober 2025")
const formattedDate = new Date(props.article.created_at).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
});


const pageUrl = computed(() => window.location.href);
const encodedPageUrl = computed(() => encodeURIComponent(window.location.href));
const encodedTitle = computed(() => encodeURIComponent(props.article.title));

const toggleLike = () => {
    router.post(
        route('articles.like', props.article.slug),
        {}, // Gak perlu ngirim data, cukup panggil endpoint-nya
        {
            preserveState: true,    
            preserveScroll: true,
        },
    )
}
</script>

<template>
    <Head :title="article.title">
        <meta property="og:type" content="article">
        <meta property="og:url" :content="pageUrl">
        <meta property="og:title" :content="article.title">
        <meta property="og:description" :content="sharingDescription">
        <meta v-if="article.cover_url" property="og:image" :content="article.cover_url">

        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" :content="pageUrl">
        <meta property="twitter:title" :content="article.title">
        <meta property="twitter:description" :content="sharingDescription">
        <meta v-if="article.cover_url" property="twitter:image" :content="article.cover_url">
    </Head>

    <Navigation :user="auth.user" :roles="auth.roles" />

    <div class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 px-4 sm:px-6 lg:px-8 py-12 lg:py-20">

            <main class="lg:col-span-8">
                <Link v-if="article.category" :href="route('home')" class="mb-4 inline-block bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded hover:bg-yellow-600 transition-colors">
                    {{ article.category.name }}
                </Link>

                <h1 class="text-3xl lg:text-4xl font-bold leading-tight mb-3">
                    {{ article.title }}
                </h1>

                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
                    <Link :href="route('users.show', article.user.username)" class="flex items-center gap-2 group">
                        <img class="h-8 w-8 rounded-full object-cover" :src="article.user.avatar ? `/storage/${article.user.avatar}` : `https://ui-avatars.com/api/?name=${article.user.name}&background=random`" :alt="article.user.name">
                        <span class="font-medium text-gray-700 dark:text-gray-300 group-hover:text-orange-500 dark:group-hover:text-orange-400 transition-colors">
                            {{ article.user.name }}
                        </span>
                    </Link>
                    <span>•</span>
                    <span>{{ formattedDate }}</span>
                    <span>•</span>
                    <span>{{ article.hits }} Hits</span>
                    <span>•</span>
                    <span>{{ article.views }} Views</span>
                </div>

                <div class="my-6 py-4 border-y border-gray-200 dark:border-gray-700">
                    <div class="flex flex-wrap items-center gap-3"> <span class="font-semibold text-gray-700 dark:text-gray-300">Share:</span>

                        <button
                            v-if="props.auth.user"
                            @click="toggleLike"
                            class="flex items-center gap-1.5 px-3 py-1 rounded-md transition-colors text-sm"
                            :class="
                                props.is_liked_by_user
                                    ? 'bg-brand text-white hover:bg-brand/90' // <--- Pake brand color lo
                                    : 'bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600'
                            "
                        >
                            <Heart
                                class="h-4 w-4"
                                :class="{ 'fill-current': props.is_liked_by_user }"
                            />
                            <span>{{ props.likes_count }}</span>
                        </button>

                        <div
                            v-else
                            class="flex items-center gap-1.5 px-3 py-1 text-sm text-gray-500 dark:text-gray-400"
                        >
                            <Heart class="h-4 w-4" />
                            <span>{{ props.likes_count }}</span>
                        </div>

                        <a :href="`https://www.facebook.com/sharer/sharer.php?u=${pageUrl}`" target="_blank" class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm">Facebook</a>
                        <a :href="`https://twitter.com/intent/tweet?url=${pageUrl}&text=${encodedTitle}`" target="_blank" class="px-3 py-1 bg-sky-500 text-white rounded-md hover:bg-sky-600 transition text-sm">Twitter</a>
                        <a :href="`https://api.whatsapp.com/send?text=${encodedTitle}%20${pageUrl}`" target="_blank" class="px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600 transition text-sm">WhatsApp</a>
                        <a :href="`https://www.linkedin.com/shareArticle?mini=true&url=${pageUrl}&title=${encodedTitle}`" target="_blank" class="px-3 py-1 bg-blue-800 text-white rounded-md hover:bg-blue-900 transition text-sm">LinkedIn</a>
                        <a :href="route('articles.export.pdf', article.slug)"
                            class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 transition text-sm">
                            Export to PDF
                        </a>
                        <a :href="route('articles.export.docx', article.slug)"
                            class="px-3 py-1 bg-blue-700 text-white rounded-md hover:bg-blue-800 transition text-sm">
                            Export to DOCX
                        </a>
                    </div>
                </div>
                

                <img v-if="article.cover" :src="`/storage/${article.cover}`" alt="Cover" class="w-full rounded-lg shadow-md mb-8" />

                <div class="prose prose-lg dark:prose-invert max-w-none" v-html="article.content"></div>

        </main>

            <aside class="lg:col-span-4">
                <div class="sticky top-24">
                    <h3 class="text-xl font-bold mb-4">Artikel Terpopuler</h3>
                    <div class="flex flex-col gap-4">
                        <Link v-for="pop in popularArticles" :key="pop.id" :href="route('articles.show', pop.slug)" class="flex gap-4 items-center group">
                            <img v-if="pop.cover" :src="`/storage/${pop.cover}`" class="w-24 h-16 rounded object-cover flex-shrink-0" />
                            <div>
                                <h4 class="font-semibold group-hover:text-orange-500 transition-colors">{{ pop.title }}</h4>
                                </div>
                        </Link>
                    </div>
                </div>
            </aside>

        </div>
    </div>

    <Footer />
</template>