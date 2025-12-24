<script setup lang="ts">
import {Form, Head} from "@inertiajs/vue3";
import { ref } from 'vue';
import posts from "@/routes/posts";
import Button from "@/components/ui/Button.vue";
import Input from "@/components/ui/Input.vue";
import Select from "@/components/ui/Select.vue";
import Textarea from "@/components/ui/Textarea.vue";

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

    <div class="flex justify-center">
        <div class="container">
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <h1 class="text-2xl">Edit post: {{ post.title }}</h1>

                <Form
                    :action="posts.delete(post.id).url"
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

                <Textarea
                    label="Body"
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
    </div>
</template>

