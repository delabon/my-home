<script setup lang="ts">
import SimplePagination from "@/components/ui/SimplePagination.vue";
import A from "@/components/ui/A.vue";
import {Link} from "@inertiajs/vue3";

defineProps({
    posts: {
        type: Object,
        required: true,
    }
});
</script>

<template>
    <div class="flex justify-center py-36">
        <div class="w-2xl">
            <div class="flex flex-col gap-2">
                <h1 class="text-3xl text-white">Hey, I'm Sabri Taieb</h1>
                <p class="text-xl">
                    An expert web developer from Tunisia with over 12 years of experience building robust and scalable applications.
                </p>
                <div class="flex items-center gap-3">
                    <A label="LinkedIn" href="https://www.linkedin.com/in/sabri-taieb/" target="_blank"/>
                    <A label="GitHub" href="https://github.com/delabon/" target="_blank"/>
                </div>
            </div>

            <div class="mt-10">
                <div v-if="posts.data.length > 0" class="flex flex-col gap-5">
                    <article v-for="post in posts.data" :key="post.id" class="flex flex-col gap-1">
                        <h3>
                            <Link href="#" class="hover:underline hover:text-white">{{ post.title}}</Link>
                        </h3>
                        <span>{{ post.created_at }}</span>
                    </article>
                </div>
                <p v-else>
                    No posts yet!
                </p>

                <div class="mt-5">
                    <SimplePagination
                        :prevUrl="posts.prev_page_url"
                        :nextUrl="posts.next_page_url"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
