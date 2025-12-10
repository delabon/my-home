<script setup lang="ts">
defineProps({
    label: {
        type: String,
        required: false,
    },
    type: {
        type: String,
        required: true,
        default: 'text',
    },
    name: {
        type: String,
        required: true,
    },
    placeholder: {
        type: String,
        required: false,
    },
    error: {
        type: String,
        required: false,
    },
});
</script>

<template>
    <label class="flex flex-col gap-2">
        <strong v-if="label">{{ label }}</strong>
        <input :type="type" :placeholder="placeholder" :name="name">
        <span v-if="error" class="text-red-500 block w-full">
            {{ error }}
        </span>
    </label>
</template>
