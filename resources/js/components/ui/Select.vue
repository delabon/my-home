<script setup lang="ts">
defineProps({
    label: {
        type: String,
        required: false,
    },
    name: {
        type: String,
        required: true,
    },
    options: {
        type: Array,
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
        <select
            :name="name"
            class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
        >
            <option v-if="placeholder" value="" disabled selected>{{ placeholder }}</option>
            <option v-for="option in options" :key="option.value" :value="option.value">
                {{ option.label }}
            </option>
        </select>
        <span v-if="error" class="text-red-500 block w-full">
            {{ error }}
        </span>
    </label>
</template>
