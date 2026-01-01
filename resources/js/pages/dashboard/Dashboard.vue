<script setup lang="ts">
import {Head, usePage} from '@inertiajs/vue3';
import DashboardLayout from "@/layouts/DashboardLayout.vue";
import DashboardMenu from "@/components/ui/DashboardMenu.vue";

const page = usePage();
</script>

<template>
    <Head title="Dashboard" />
    <DashboardLayout>
        <div class="flex flex-col gap-3">
            <DashboardMenu/>
            <h1 class="text-3xl text-white">Welcome, {{ page.props.auth.user.name }}!</h1>
        </div>

        <div class="mt-10"></div>
    </DashboardLayout>
</template>
