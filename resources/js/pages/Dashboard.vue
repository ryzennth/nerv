<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import Swal from 'sweetalert2';
import axios from 'axios';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

// Daftarkan komponen Chart.js yang akan kita gunakan
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

// --- KODE LAMA ANDA YANG DIPERTAHANKAN ---
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const page = usePage();
const flash = page.props?.flash?.message;
if (flash) {
    let timerInterval: any;
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

// --- KODE BARU UNTUK STATISTIK ---
const stats = ref<any>(null);
const isLoading = ref(true);

// Ambil data dari backend saat komponen dimuat
onMounted(async () => {
    try {
        const response = await axios.get(route('dashboard.stats'));
        stats.value = response.data;
    } catch (error) {
        console.error('Failed to fetch dashboard stats:', error);
    } finally {
        isLoading.value = false;
    }
});

// Format data agar bisa dibaca oleh komponen Bar chart
const chartData = computed(() => {
    if (!stats.value?.chart) {
        return { labels: [], datasets: [] };
    }
    return {
        labels: stats.value.chart.labels,
        datasets: [
            {
                label: 'Articles Published',
                backgroundColor: '#f97316', // Warna oranye agar cocok
                data: stats.value.chart.data,
                borderRadius: 4,
            },
        ],
    };
});

// Opsi untuk chart
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div v-if="isLoading" class="text-center text-gray-500 py-10">
                Loading dashboard data...
            </div>

            <div v-else-if="stats" class="flex h-full flex-1 flex-col gap-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ stats.stats.total_users }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Published Articles</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ stats.stats.total_articles }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Writers</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ stats.stats.total_writers }}</p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Hits</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ stats.stats.total_hits }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Views</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ stats.stats.total_views }}</p>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Filter Data</h3>
                        <div class="flex flex-wrap items-center gap-4">
                            <div>
                                <label for="from_date" class="text-sm text-gray-600 dark:text-gray-400">From</label>
                                <input type="date" id="from_date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-white">
                            </div>
                            <div>
                                <label for="to_date" class="text-sm text-gray-600 dark:text-gray-400">To</label>
                                <input type="date" id="to_date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-white">
                            </div>
                            <button class="self-end px-4 py-2 bg-orange-500 text-white rounded-md">Apply</button>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Articles per Month (Last 12 Months)</h3>
                        <div class="h-96">
                            <Bar :data="chartData" :options="chartOptions" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>