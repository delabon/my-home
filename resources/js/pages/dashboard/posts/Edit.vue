<script setup lang="ts">
import {Form, Head} from "@inertiajs/vue3";
import { ref } from 'vue';
import posts from "@/routes/posts";
import Button from "@/components/ui/Button.vue";
import Input from "@/components/ui/Input.vue";
import Select from "@/components/ui/Select.vue";
import DashboardLayout from "@/layouts/DashboardLayout.vue";
import DashboardMenu from "@/components/ui/DashboardMenu.vue";
import Tiptap from "@/components/ui/Tiptap.vue";

const props = defineProps({
    post: {
        type: Object,
        required: true,
    },
    statuses: {
        type: Object,
        required: true,
    }
});

const title = ref(props.post.title || '');
const slug = ref(props.post.slug || '');
const body = ref(props.post.body || '');
const status = ref(props.post.status || 'draft');
</script>

<template>
    <Head title="Edit Post"/>

    <DashboardLayout>
        <div class="flex flex-col gap-3">
            <DashboardMenu/>

            <div class="flex items-center justify-between flex-wrap gap-3">
                <h1 class="text-3xl text-white">Edit post: {{ post.title }}</h1>

                <Form
                    :action="posts.destroy(post.id).url"
                    method="delete"
                    #default="{
                            processing,
                        }"
                    class="flex flex-col gap-4 mt-6"
                >
                    <Button
                        :label="processing ? 'Deleting...' : 'Delete Post'"
                        type="submit"
                    />
                </Form>
            </div>
        </div>

        <div class="mt-10">
            <Form
                :action="posts.update(post.id).url"
                method="patch"
                #default="{
                    errors,
                    processing,
                }"
                class="flex flex-col gap-4 mt-6"
                resetOnSuccess
            >
                <Input
                    label="Title"
                    type="text"
                    name="title"
                    v-model="title"
                    :error="errors.title"
                    autocomplete="title"
                />

                <Input
                    label="Slug"
                    type="text"
                    name="slug"
                    v-model="slug"
                    :error="errors.slug"
                    autocomplete="slug"
                />

                <Tiptap
                    label="Body (Markdown)"
                    name="body"
                    v-model="body"
                    :error="errors.body"
                />

                <Select
                    label="Status"
                    name="status"
                    v-model="status"
                    :options="Object.values(statuses)"
                    placeholder="Select..."
                    :error="errors.status"
                />

                <Button
                    :label="processing ? 'Saving...' : 'Save'"
                    type="submit"
                />
            </Form>
        </div>
    </DashboardLayout>
</template>

