<script setup lang="ts">
import A from "@/components/ui/A.vue";
import {Link, usePage} from "@inertiajs/vue3";
import {home} from "@/routes";

const page = usePage();
</script>

<template>
    <nav class="flex items-center gap-2">
        <Link :href="home.url()" class="hover:underline hover:text-white">Home</Link>
        <span v-if="page.props.links.linkedin" class="text-2xl">&bull;</span>
        <A v-if="page.props.links.linkedin" label="LinkedIn" :href="page.props.links.linkedin" target="_blank"/>
        <span v-if="page.props.links.github" class="text-2xl">&bull;</span>
        <A v-if="page.props.links.github" label="GitHub" :href="page.props.links.github" target="_blank"/>
    </nav>
</template>
