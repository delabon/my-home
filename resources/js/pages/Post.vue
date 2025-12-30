<script setup lang="ts">
import FrontendLayout from "@/layouts/FrontendLayout.vue";
import {Head, Link} from "@inertiajs/vue3";

const props = defineProps({
    post: {
        type: Object,
        required: true,
    }
});

console.log(props);
</script>

<template>
    <Head :title="`${post.data.title} - Blog`"/>
    <FrontendLayout>
        <div class="flex flex-col gap-1">
            <Link href="/" class="hover:underline hover:text-white">Back home</Link>
            <h1 class="text-3xl text-white">{{ post.data.title }}</h1>
            <span>{{ post.data.formatted_created_at }}</span>
        </div>

        <div class="mt-8">
            {{ post.data.body }}
        </div>
    </FrontendLayout>
</template>
