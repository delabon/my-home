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
    <button class="cursor-pointer" :type="type">
        {{ label }}
    </button>
</template>
