<script setup lang="ts">
import A from "@/components/ui/A.vue";
import {Link} from "@inertiajs/vue3";
import {home} from "@/routes";

</script>

<template>
    <nav class="flex items-center gap-2">
        <Link :href="home.url()" class="hover:underline hover:text-white">Home</Link>
        <span class="text-2xl">&bull;</span>
        <A label="LinkedIn" href="https://www.linkedin.com/in/sabri-taieb/" target="_blank"/>
        <span class="text-2xl">&bull;</span>
        <A label="GitHub" href="https://github.com/delabon/" target="_blank"/>
    </nav>
</template>
