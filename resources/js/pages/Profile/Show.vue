<script setup>
import { Head, Link , usePage} from '@inertiajs/vue3';
import Navigation from '@/components/Navigation.vue';
import Footer from '@/components/Footer.vue';

const props = defineProps({
    user: Object,
});

const page = usePage();

</script>

<template>
    <Head :title="`${user.name}'s Profile`" />

    <Navigation :user="page.props.auth.user" :roles="page.props.auth.roles" />
        <div class="bg-white dark:bg-gray-900 min-h-screen">

            <div class="relative h-48 sm:h-64 w-full">
                <img
                    v-if="user.banner"
                    :src="`/storage/${user.banner}`"
                    :alt="`${user.name}'s banner`"
                    class="w-full h-full object-cover"
                />
                <div v-else class="w-full h-full bg-gray-200 dark:bg-gray-700"></div>

                <div class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row items-center sm:items-end gap-4">
                        <div class="flex-shrink-0">
                            <img
                                class="h-28 w-28 sm:h-36 sm:w-36 rounded-full object-cover ring-4 ring-white dark:ring-gray-900"
                                :src="user.avatar ? `/storage/${user.avatar}` : `https://ui-avatars.com/api/?name=${user.name}&background=random&size=144`"
                                :alt="user.name"
                            />
                        </div>
                        <div class="text-center sm:text-left sm:mb-4">
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ user.name }}</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">@{{ user.username }}</p>
                            <p v-if="user.bio" class="text-base text-gray-700 dark:text-gray-300 mt-2">{{ user.bio }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-24 sm:pt-28">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Articles by {{ user.name }}</h2>
                    <div v-if="user.articles && user.articles.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="article in user.articles" :key="article.id" class="bg-gray-50 dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                            <Link :href="route('articles.show', article.slug)">
                                <img v-if="article.cover" :src="`/storage/${article.cover}`" class="w-full h-40 object-cover hover:opacity-90 transition-opacity" />
                                <div class="p-4">
                                    <h3 class="font-bold text-gray-900 dark:text-white">{{ article.title }}</h3>
                                </div>
                            </Link>
                        </div>
                    </div>
                    <div v-else class="text-center py-12">
                        <p class="text-gray-500 dark:text-gray-400">{{ user.name }} has not published any articles yet.</p>
                    </div>
                </div>
            </div>

        </div>
    <Footer />
</template>