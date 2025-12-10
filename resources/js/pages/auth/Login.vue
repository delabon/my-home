<script setup lang="ts">
import { Head, Form } from '@inertiajs/vue3';
import Input from "@/components/ui/Input.vue";

</script>

<template>
    <Head title="Login"/>
    <div>
        <h1>Login</h1>

        <Form
            action="/login"
            method="post"
            #default="{
                errors,
                processing,
            }"
            class="flex flex-col gap-4 mt-6"
            resetOnSuccess
        >
            <Input
                label="Email"
                type="email"
                placeholder="john@example.com"
                name="email"
                :error="errors.email"
            />

            <Input
                label="Password"
                type="password"
                placeholder="********"
                name="password"
                :error="errors.password"
            />

            <button class="cursor-pointer" type="submit">
                {{ processing ? "Signing-in..." : "Sign-In" }}
            </button>
        </Form>
    </div>
</template>
