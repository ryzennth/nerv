<script setup>
import { onMounted, onUnmounted, ref, watch } from 'vue';
import TomSelect from 'tom-select';

// Komponen ini menerima props:
// - modelValue: Nilai yang dipilih (untuk v-model)
// - options: Daftar pilihan yang tersedia
// - multiple: Apakah bisa memilih lebih dari satu
const props = defineProps({
    modelValue: [String, Number, Array],
    options: {
        type: Array,
        default: () => [],
    },
    multiple: {
        type: Boolean,
        default: false,
    },
});

// Mendefinisikan event untuk v-model
const emit = defineEmits(['update:modelValue']);

const selectElement = ref(null);
let tomSelectInstance = null;

onMounted(() => {
    if (selectElement.value) {
        tomSelectInstance = new TomSelect(selectElement.value, {
            plugins: props.multiple ? ['remove_button'] : [],
            items: props.modelValue,
            options: props.options.map(opt => ({ value: opt.id, text: opt.name })),
            onChange: (value) => {
                emit('update:modelValue', value);
            },
        });
    }
});

// Menjaga agar Tom Select tetap sinkron jika props berubah
watch(() => props.modelValue, (newValue) => {
    if (tomSelectInstance && tomSelectInstance.getValue() !== newValue) {
        tomSelectInstance.setValue(newValue);
    }
});

// Hancurkan instance saat komponen dibongkar untuk mencegah memory leak
onUnmounted(() => {
    if (tomSelectInstance) {
        tomSelectInstance.destroy();
    }
});
</script>

<template>
    <select ref="selectElement" :multiple="props.multiple">
        </select>
</template>

<style>
/* Impor styling default dari Tom Select */
@import 'tom-select/dist/css/tom-select.default.css';

/* Kustomisasi agar cocok dengan tema gelap (opsional) */
.ts-wrapper .ts-control {
    background-color: transparent !important;
    border-color: #4a5568; /* dark:border-gray-600 */
}
.ts-wrapper.multi .ts-control > div {
    background: #f97316; /* bg-orange-500 */
    color: white;
}
</style>