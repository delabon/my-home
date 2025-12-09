<script setup lang="ts">
import SimplePagination from "@/components/ui/SimplePagination.vue";

defineProps({
    posts: {
        type: Object,
        required: true,
    }
});
</script>

<template>
    <div>
        <div v-if="posts.data.length > 0">
            <article v-for="post in posts.data" :key="post.id">
                <h3>{{ post.title}}</h3>
            </article>
        </div>
        <p v-else>
            No posts yet!
        </p>

        <SimplePagination
            :prevUrl="posts.prev_page_url"
            :nextUrl="posts.next_page_url"
        />
    </div>
</template>
