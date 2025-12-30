<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps({
    nextUrl: {
        type: [String, null],
        required: true,
    },
    prevUrl: {
        type: [String, null],
        required: true,
    },
});
</script>

<template>
    <nav>
        <Link :href="prevUrl" v-if="prevUrl" class="hover:underline hover:text-white">Prev</Link>
        <Link :href="nextUrl" v-if="nextUrl" class="hover:underline hover:text-white">Next</Link>
    </nav>
</template>
