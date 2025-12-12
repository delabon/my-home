<script setup lang="ts">
import { Head, Form } from '@inertiajs/vue3';
import posts from "@/routes/posts";
import Input from "@/components/ui/Input.vue";
import Textarea from "@/components/ui/Textarea.vue";
import Button from "@/components/ui/Button.vue";
import Select from "@/components/ui/Select.vue";

defineProps({
    statuses: {
        type: Object,
        required: true,
    }
});
</script>

<template>
    <Head title="Create a New Post"/>

    <div class="flex justify-center">
        <div class="container">
            <h1 class="text-2xl">Create a new post</h1>

            <Form
                :action="posts.store().url"
                method="post"
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
                    :error="errors.title"
                    autocomplete="title"
                />

                <Textarea
                    label="Body"
                    name="body"
                    :error="errors.body"
                />

                <Select
                    label="Status"
                    name="status"
                    :options="Object.values(statuses)"
                    placeholder="Select..."
                    :error="errors.status"
                />

                <Button
                    :label="processing ? 'Creating...' : 'Create'"
                    type="submit"
                />
            </Form>
        </div>
    </div>
</template>
