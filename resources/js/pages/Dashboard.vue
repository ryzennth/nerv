<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types'; // Pertahankan tipe jika digunakan di tempat lain
import Swal from 'sweetalert2';
import axios from 'axios';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ChartData, ChartOptions } from 'chart.js';

// Daftarkan komponen Chart.js
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

// --- State & Props ---
interface StatsData {
  stats: {
    total_users: number;
    total_articles: number;
    total_writers: number;
    total_hits: number;
    total_views: number;
  };
  charts: {
    articles: { labels: string[]; data: number[] };
    albumViews: { labels: string[]; data: number[] };
    photoHits: { labels: string[]; data: number[] };
  };
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard', href: '/dashboard' }];
const page = usePage();
const stats = ref<StatsData | null>(null); // Tambahkan tipe data
const isLoading = ref(true);
const filters = ref({ from_date: '', to_date: '' });

// --- Logika Flash Message Swal ---
const flash = page.props?.flash?.message;
if (flash) {
    let timerInterval: ReturnType<typeof setInterval>;
    Swal.fire({
        title: "Selamat Datang",
        icon: "success",
        html: "Login Berhasil dilaksanakan",
        timer: 1000,
        timerProgressBar: true,
        didOpen: () => {
            const timer = Swal.getPopup()!.querySelector("b");
            timerInterval = setInterval(() => {
                if (timer) {
                    timer.textContent = `${Swal.getTimerLeft()}`;
                }
            }, 100);
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
    });
}

// --- Fungsi Fetch Data ---
const fetchStats = async () => {
    isLoading.value = true;
    try {
        const response = await axios.get(route('dashboard.stats'), { params: filters.value });
        stats.value = response.data as StatsData; // Assert tipe data
    } catch (error) {
        console.error('Failed to fetch dashboard stats:', error);
        // Tambahkan notifikasi error ke user jika perlu
    } finally {
        isLoading.value = false;
    }
};

onMounted(fetchStats);

// --- Computed Properties untuk Chart ---
// Fungsi helper untuk membuat data chart
const createChartData = (chartKey: keyof StatsData['charts'], label: string, backgroundColor: string): ChartData<'bar'> => {
  const chartInfo = stats.value?.charts?.[chartKey];
  if (!chartInfo) return { labels: [], datasets: [] };
  return {
    labels: chartInfo.labels,
    datasets: [{ label, backgroundColor, data: chartInfo.data, borderRadius: 4 }],
  };
};

const articlesChartData = computed(() => createChartData('articles', 'Articles Published', '#f97316'));
const albumViewsChartData = computed(() => createChartData('albumViews', 'Album Views', '#3b82f6'));
const photoHitsChartData = computed(() => createChartData('photoHits', 'Photo Hits', '#10b981'));

// Opsi chart (tipe ditambahkan)
const chartOptions = computed<ChartOptions<'bar'>>(() => ({
    responsive: true,
    maintainAspectRatio: false,
    // Tambahkan opsi lain jika perlu, misal: scales, plugins, dll.
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                 color: page.props.theme === 'dark' ? '#cbd5e1' : '#6b7280', // Sesuaikan warna ticks
                 precision: 0 // Tampilkan angka bulat
            },
            grid: {
                color: page.props.theme === 'dark' ? '#374151' : '#e5e7eb' // Sesuaikan warna grid
            }
        },
        x: {
            ticks: {
                 color: page.props.theme === 'dark' ? '#cbd5e1' : '#6b7280'
            },
            grid: {
                display: false // Sembunyikan grid vertikal
            }
        }
    },
    plugins: {
        legend: {
            labels: {
                color: page.props.theme === 'dark' ? '#cbd5e1' : '#374151' // Sesuaikan warna legenda
            }
        },
        tooltip: {
            backgroundColor: page.props.theme === 'dark' ? '#1f2937' : '#ffffff',
            titleColor: page.props.theme === 'dark' ? '#f3f4f6' : '#111827',
            bodyColor: page.props.theme === 'dark' ? '#d1d5db' : '#374151',
        }
    }
}));

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div v-if="isLoading" class="flex justify-center items-center h-64">
                <svg class="animate-spin h-8 w-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="ml-3 text-gray-500">Loading dashboard data...</span>
            </div>

            <div v-else-if="stats" class="flex flex-col gap-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ stats.stats.total_users }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Published Articles</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ stats.stats.total_articles }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Writers</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ stats.stats.total_writers }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Article Hits</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ stats.stats.total_hits }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Article Views</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ stats.stats.total_views }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Filter Data</h3>
                    <div class="flex flex-wrap items-center gap-4">
                        <div>
                            <label for="from_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">From</label>
                            <input v-model="filters.from_date" type="date" id="from_date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-300 focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="to_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">To</label>
                            <input v-model="filters.to_date" type="date" id="to_date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-300 focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                        </div>
                        <button @click="fetchStats" class="self-end px-4 py-2 bg-orange-500 text-white rounded-md shadow-sm hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">Apply</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Articles Published</h3>
                        <div class="h-80 relative">
                            <Bar :data="articlesChartData" :options="chartOptions.value" />
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Album Views</h3>
                        <div class="h-80 relative">
                            <Bar :data="albumViewsChartData" :options="chartOptions.value" />
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Photo Hits</h3>
                        <div class="h-80 relative">
                            <Bar :data="photoHitsChartData" :options="chartOptions.value" />
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center text-red-500 py-10">
                Failed to load dashboard data. Please try again later.
            </div>
        </div>
    </AppLayout>
</template>