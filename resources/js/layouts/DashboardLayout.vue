<script setup lang="ts">
import Copyright from "@/components/ui/Copyright.vue";
</script>

<template>
    <div>
        <slot />

        <Copyright classes="w-2xl text-center"/>
    </div>
</template>
