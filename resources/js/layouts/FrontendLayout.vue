<script setup lang="ts">
import {usePage} from "@inertiajs/vue3";

const page = usePage();
</script>

<template>
    <div class="flex flex-col gap-10 items-center justify-center py-36">
        <div class="w-2xl">
            <slot />
        </div>

        <div class="w-2xl">
            &copy; {{ new Date().getFullYear() }} - {{ page.props.name }} - All rights reserved
        </div>
    </div>
</template>
