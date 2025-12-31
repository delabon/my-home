<script setup lang="ts">
import Copyright from "@/components/ui/Copyright.vue";
</script>

<template>
    <div class="flex flex-col gap-10 items-center justify-center py-36">
        <div class="w-2xl">
            <slot />
        </div>

        <Copyright classes="w-2xl"/>
    </div>
</template>
