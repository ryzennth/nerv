<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import Navigation from '@/components/Navigation.vue';
import Footer from '@/components/Footer.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    user: Object,
    popularArticles: Array,
});

const page = usePage();

// State untuk tab aktif
const activeTab = ref('all');

// Menghitung jumlah artikel yang sudah di-publish
const publishedCount = computed(() => props.user.articles?.length || 0);

// Format tanggal untuk "Member Since"
const memberSince = new Date(props.user.created_at).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
});
</script>

<template>
    <Head :title="`${user.name}'s Profile`" />

    <Navigation :user="page.props.auth.user" :roles="page.props.auth.roles" />

    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen mt-15">
        <div class="h-48 sm:h-60 w-full bg-gray-200 dark:bg-gray-800">
            <img
                v-if="user.banner"
                :src="`/storage/${user.banner}`"
                :alt="`${user.name}'s banner`"
                class="w-full h-full object-cover"
            />
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <aside class="lg:col-span-4">
                    <div class="-mt-16 sm:-mt-20">
                        <img
                            class="h-28 w-28 sm:h-36 sm:w-36 rounded-full object-cover ring-4 ring-white dark:ring-gray-900 mx-auto lg:mx-0 shadow-lg"
                            :src="user.avatar ? `/storage/${user.avatar}` : `https://ui-avatars.com/api/?name=${user.name}&background=random&size=144`"
                            :alt="user.name"
                        />
                    </div>

                    <div class="mt-4 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ user.name }}</h1>
                        <p class="text-sm text-orange-500 font-semibold mt-1">@{{ user.username }}</p>

                        <div class="mt-6">
                            <h3 class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400 tracking-wider">About Author</h3>
                            <p v-if="user.bio" class="text-base text-gray-700 dark:text-gray-300 mt-2">{{ user.bio }}</p>
                            <p v-else class="text-sm text-gray-500 italic mt-2">No bio provided yet.</p>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400 tracking-wider">Info Author</h3>
                            <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Member Since</p>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">{{ memberSince }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Published</p>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">{{ publishedCount }} Articles</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

                <main class="lg:col-span-8 mt-4 lg:mt-0">
                    <div class="flex gap-4 mb-8">
                        <button
                            @click="activeTab = 'all'"
                            :class="[activeTab === 'all' ? 'bg-orange-500 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700']"
                            class="px-5 py-2 rounded-md font-semibold transition-colors shadow-sm"
                        >
                            All Post
                        </button>
                        <button
                            @click="activeTab = 'popular'"
                            :class="[activeTab === 'popular' ? 'bg-orange-500 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700']"
                            class="px-5 py-2 rounded-md font-semibold transition-colors shadow-sm"
                        >
                            Popular
                        </button>
                    </div>

                    <div v-if="activeTab === 'all'">
                        <div v-if="user.articles && user.articles.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div v-for="article in user.articles" :key="article.id" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                <Link :href="route('articles.show', article.slug)">
                                    <img v-if="article.cover" :src="`/storage/${article.cover}`" class="w-full h-40 object-cover" />
                                    <div class="p-4">
                                        <h3 class="font-bold text-gray-900 dark:text-white line-clamp-2">{{ article.title }}</h3>
                                    </div>
                                </Link>
                            </div>
                        </div>
                        <div v-else class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                            <p class="text-lg text-gray-500 dark:text-gray-400">{{ user.name }} has not published any articles yet.</p>
                        </div>
                    </div>

                    <div v-else-if="activeTab === 'popular'">
                        <div v-if="popularArticles && popularArticles.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div v-for="article in popularArticles" :key="article.id" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                <Link :href="route('articles.show', article.slug)">
                                    <img v-if="article.cover" :src="`/storage/${article.cover}`" class="w-full h-40 object-cover" />
                                    <div class="p-4">
                                        <h3 class="font-bold text-gray-900 dark:text-white line-clamp-2">{{ article.title }}</h3>
                                    </div>
                                </Link>
                            </div>
                        </div>
                        <div v-else class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                            <p class="text-lg text-gray-500 dark:text-gray-400">No popular articles found for {{ user.name }}.</p>
                        </div>
                    </div>
                </main>

            </div>
        </div>
    </div>

    <Footer />
</template>