<script setup lang="ts">
import { Head, Form } from '@inertiajs/vue3';
import Input from "@/components/ui/Input.vue";
import Button from "@/components/ui/Button.vue";
import { login } from "@/routes";
import AuthLayout from "@/layouts/AuthLayout.vue";

</script>

<template>
    <Head title="Login"/>
    <AuthLayout>
        <h1 class="text-3xl text-white">Login</h1>

        <Form
            :action="login().url"
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
                autocomplete="email"
            />

            <Input
                label="Password"
                type="password"
                placeholder="********"
                name="password"
                :error="errors.password"
                autocomplete="current-password"
            />

            <Button
                :label="processing ? 'Signing-in...' : 'Sign-In'"
                type="submit"
            />
        </Form>
    </AuthLayout>
</template>
