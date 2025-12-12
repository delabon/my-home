<script setup lang="ts">
defineProps({
    label: {
        type: String,
        required: false,
    },
    type: {
        type: String,
        required: true,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="text-black bg-gray-400 p-3 rounded cursor-pointer"
    >
        {{ label }}
    </button>
</template>
