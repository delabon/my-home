<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

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
        <Link :href="prevUrl" v-if="prevUrl">Prev</Link>
        <Link :href="nextUrl" v-if="nextUrl">Next</Link>
    </nav>
</template>
