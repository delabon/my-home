<script setup lang="ts">
import SimplePagination from "@/components/ui/SimplePagination.vue";
import {Link} from "@inertiajs/vue3";
import FrontendLayout from "@/layouts/FrontendLayout.vue";
import Menu from "@/components/ui/Menu.vue";

defineProps({
    posts: {
        type: Object,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    }
});
</script>

<template>
    <frontend-layout>
        <div class="flex flex-col gap-2">
            <h1 class="text-3xl text-white">{{ title }}</h1>
            <p class="text-xl">{{ description }}</p>
            <Menu/>
        </div>

        <div class="mt-10">
            <div v-if="posts.data.length > 0" class="flex flex-col gap-5">
                <article v-for="post in posts.data" :key="post.id" class="flex flex-col gap-1">
                    <h3 class="text-xl">
                        <Link :href="`/blog/${post.slug}`" class="hover:underline hover:text-white">{{ post.title }}</Link>
                    </h3>
                    <span class="text-sm">{{ post.formatted_published_at }}</span>
                </article>
            </div>
            <p v-else>
                No posts yet!
            </p>

            <div class="mt-5">
                <SimplePagination
                    :prevUrl="posts.links.prev"
                    :nextUrl="posts.links.next"
                />
            </div>
        </div>
    </frontend-layout>
</template>
