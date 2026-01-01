<script setup lang="ts">
import {Link, usePage} from "@inertiajs/vue3";
import {dashboard, home} from "@/routes";
import posts from "@/routes/posts";

const page = usePage();
</script>

<template>
    <nav class="flex items-center gap-2">
        <Link :href="home.url()" class="hover:underline hover:text-white">Home</Link>
        <span class="text-2xl">&bull;</span>
        <Link :href="dashboard.url()" class="hover:underline hover:text-white">Dashboard</Link>
        <span class="text-2xl">&bull;</span>
        <Link :href="posts.index().url" class="hover:underline hover:text-white">Posts</Link>
    </nav>
</template>
