<script setup lang="ts">
import {Head, usePage} from "@inertiajs/vue3";

const flash = usePage().props.flash;

defineProps({
    posts: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head title="All Posts"/>

    <div>
        <h1>All Posts</h1>

        <div v-if="posts.data.length > 0">
            <ul>
                <li v-for="post in posts.data" :key="post.id">
                    <h3 v-text="post.title"></h3>
                </li>
            </ul>
            <div v-if="flash.success" class="p-4 my-4 text-green-800 bg-green-100 border border-green-200 rounded-base">
                ✓ {{ flash.success }}
            </div>
        </div>
        <div v-else>
            No posts yet!
        </div>
    </div>
</template>
