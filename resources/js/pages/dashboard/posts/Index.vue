<script setup lang="ts">
import {usePage} from "@inertiajs/vue3";

const flash = usePage().props.flash;
</script>

<template>
    <div>
        <div v-if="flash.success" class="p-4 my-4 text-green-800 bg-green-100 border border-green-200 rounded-base">
            ✓ {{ flash.success }}
        </div>
    </div>
</template>
