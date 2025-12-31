<script setup lang="ts">
import {usePage} from "@inertiajs/vue3";
import Copyright from "@/components/ui/Copyright.vue";

const page = usePage();
</script>

<template>
    <div class="flex flex-col gap-10 items-center justify-center py-36">
        <div class="w-2xl">
            <slot />
        </div>

        <Copyright classes="w-2xl"/>
    </div>
</template>
