<script setup lang="ts">
import {usePage} from "@inertiajs/vue3";

const page = usePage();

defineProps({
    classes: {
        type: String,
        required: false,
    },
})
</script>

<template>
    <div :class="classes">
        &copy; {{ new Date().getFullYear() }} - {{ page.props.name }} - All rights reserved
    </div>
</template>
