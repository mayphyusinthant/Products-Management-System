<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="border-gray-300 focus:border-pink-900 focus:ring-pink-900 rounded-md shadow-sm mx-3 min-w-28"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>
