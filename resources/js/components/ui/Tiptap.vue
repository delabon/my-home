<script setup lang="ts">
import StarterKit from '@tiptap/starter-kit'
import { useEditor, EditorContent } from '@tiptap/vue-3'
import { watch } from 'vue'

interface Props {
    modelValue?: string
    label?: string | null
    name: string
    error?: string | null
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: '',
    label: null,
    error: null,
})

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void
}>()

const editor = useEditor({
    extensions: [StarterKit],
    content: props.modelValue,
    onUpdate: () => {
        if (editor.value) {
            emit('update:modelValue', editor.value.getHTML())
        }
    },
})

watch(() => props.modelValue, (value) => {
    if (!editor.value) {
        return
    }

    const isSame = editor.value.getHTML() === value

    if (isSame) {
        return
    }

    editor.value.commands.setContent(value ?? '', false)
})
</script>

<template>
    <label class="flex flex-col gap-2">
        <strong v-if="label">{{ label }}</strong>

        <editor-content
            :editor="editor"
            :class="`tiptap-${name} bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body`" />

        <span v-if="error" class="text-red-500 block w-full">
            {{ error }}
        </span>
    </label>

    <textarea
        class="hidden"
        :name="name"
    >{{ props.modelValue }}</textarea>
</template>

<style lang="scss">
/* Basic editor styles */
.tiptap {
    padding: 0;
    margin: 0;
    min-height: 250px;

    &:focus {
        border: 0 !important;
        outline: 0 !important;
    }

    :first-child {
        margin-top: 0;
    }

    /* List styles */
    ul,
    ol {
        padding: 0 1rem;
        margin: 1.25rem 1rem 1.25rem 0.4rem;

        li p {
            margin-top: 0.25em;
            margin-bottom: 0.25em;
        }
    }

    ol {
        list-style-type: decimal;
    }

    ul {
        list-style-type: disc;
    }

    /* Heading styles */
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        line-height: 1.1;
        margin-top: 1.5rem;
        text-wrap: pretty;
    }

    h1,
    h2 {
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
    }

    h1 {
        font-size: 1.4rem;
    }

    h2 {
        font-size: 1.2rem;
    }

    h3 {
        font-size: 1.1rem;
    }

    h4,
    h5,
    h6 {
        font-size: 1rem;
    }

    /* Code and preformatted text styles */
    code {
        background-color: var(--purple-light);
        border-radius: 0.4rem;
        color: var(--black);
        font-size: 0.85rem;
        padding: 0.25em 0.3em;
    }

    pre {
        background: var(--black);
        border-radius: 0.5rem;
        color: var(--white);
        font-family: 'JetBrainsMono', monospace;
        margin: 1.5rem 0;
        padding: 0.75rem 1rem;

        code {
            background: none;
            color: inherit;
            font-size: 0.8rem;
            padding: 0;
        }
    }

    blockquote {
        border-left: 3px solid var(--gray-3);
        margin: 1.5rem 0;
        padding-left: 1rem;
    }

    hr {
        border: none;
        border-top: 1px solid var(--gray-2);
        margin: 2rem 0;
    }
}
</style>
