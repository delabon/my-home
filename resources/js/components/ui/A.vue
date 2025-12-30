<script setup lang="ts">
defineProps({
    label: {
        type: String,
        required: true,
    },
    href: {
        type: String,
        required: true,
    },
    target: {
        type: String,
        required: false,
        default: '_self',
    },
});
</script>

<template>
    <a
        :href="href"
        :target="target"
        class="hover:underline hover:text-white"
    >
        {{ label }}
    </a>
</template>
