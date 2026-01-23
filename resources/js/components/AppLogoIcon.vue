<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <img
        src="/icons/sf.png"
        alt="sf icon"
        :class="className"
        v-bind="$attrs"
    />
</template>
