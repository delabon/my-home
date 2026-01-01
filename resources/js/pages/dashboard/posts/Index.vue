<script setup lang="ts">
import {Head, Link, usePage} from "@inertiajs/vue3";
import DashboardLayout from "@/layouts/DashboardLayout.vue";
import DashboardMenu from "@/components/ui/DashboardMenu.vue";
import {edit as postEditRoute, create as postCreateRoute} from "@/routes/posts";
import SimplePagination from "@/components/ui/SimplePagination.vue";

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

    <DashboardLayout>
        <div class="flex flex-col gap-3">
            <DashboardMenu/>
            <div class="flex items-center justify-between flex-wrap gap-3">
                <h1 class="text-3xl text-white">All Posts</h1>

                <Link :href="postCreateRoute()" type="button" class="text-black bg-gray-400 p-3 rounded cursor-pointer">Create Post</Link>
            </div>
        </div>

        <div class="mt-10">
            <div v-if="flash.success" class="p-4 my-4 text-green-800 bg-green-100 border border-green-200 rounded-base">
                ✓ {{ flash.success }}
            </div>

            <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default" v-if="posts.data.length > 0">
                <table class="w-full text-sm text-left rtl:text-right text-body">
                    <thead class="text-sm text-body bg-neutral-secondary-soft border-b rounded-base border-default">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-bold text-white">ID</th>
                            <th scope="col" class="px-6 py-3 font-bold text-white">Title</th>
                            <th scope="col" class="px-6 py-3 font-bold text-white">Status</th>
                            <th scope="col" class="px-6 py-3 font-bold text-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="post in posts.data" :key="post.id">
                            <td class="px-6 py-4" v-text="post.id"></td>
                            <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap" v-text="post.title"></th>
                            <td class="px-6 py-4" v-text="post.status.charAt(0).toUpperCase() + post.status.slice(1)"></td>
                            <td class="px-6 py-4">
                                <Link :href="postEditRoute(post.id)" class="hover:underline hover:text-white">Edit</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-else>
                No posts yet!
            </div>

            <div class="mt-5">
                <SimplePagination
                    :prevUrl="posts.links.prev"
                    :nextUrl="posts.links.next"
                />
            </div>
        </div>
    </DashboardLayout>
</template>
