<script setup lang="ts">
import FrontendLayout from "@/layouts/FrontendLayout.vue";
import {Head} from "@inertiajs/vue3";
import Menu from "@/components/ui/Menu.vue";

defineProps({
    post: {
        type: Object,
        required: true,
    }
});
</script>

<template>
    <Head :title="`${post.data.title} - Blog`"/>
    <FrontendLayout>
        <div class="flex flex-col gap-3">
            <Menu/>
            <h1 class="text-3xl text-white">{{ post.data.title }}</h1>
            <span>{{ post.data.formatted_created_at }}</span>
        </div>

        <div class="mt-10">
            {{ post.data.body }}
        </div>
    </FrontendLayout>
</template>
