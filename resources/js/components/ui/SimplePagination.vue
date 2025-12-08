<script setup lang="ts">
defineProps({
    nextUrl: {
        type: String,
        required: true,
    },
    prevUrl: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <nav>
        <a :href="prevUrl" v-if="prevUrl">Prev</a>
        <a :href="nextUrl" v-if="nextUrl">Next</a>
    </nav>
</template>
