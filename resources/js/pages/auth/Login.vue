<script setup lang="ts">
import { Head, Form } from '@inertiajs/vue3';

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
            <label class="flex flex-col gap-2">
                <strong>Email</strong>
                <Input type="email" placeholder="john@example.com" name="email"/>
                <div v-if="errors.email" class="text-red-500">
                    {{ errors.email }}
                </div>
            </label>

            <label class="flex flex-col gap-2">
                <strong>Password</strong>
                <Input type="password" placeholder="********" name="password"/>
                <div v-if="errors.password" class="text-red-500">
                    {{ errors.password }}
                </div>
            </label>

            <button class="cursor-pointer" type="submit">
                {{ processing ? "Signing-in..." : "Sign-In" }}
            </button>
        </Form>
    </div>
</template>
