<script setup lang="ts">
const model = defineModel<string>({ required: false });

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
    autocomplete: {
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
        <input
            :type="type"
            :placeholder="placeholder"
            :name="name"
            v-model="model"
            :autocomplete="autocomplete"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
        >
        <span v-if="error" class="text-red-500 block w-full">
            {{ error }}
        </span>
    </label>
</template>
