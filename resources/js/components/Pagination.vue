<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

defineProps<{
    links: any[];
}>();
</script>

<template>
    <div class="flex items-center justify-center gap-2">
        <Link v-for="link in links" :key="link.label" :href="link.url || '#'" :active="link.active">
            <Button 
                :variant="link.active ? 'default' : 'outline'"
                size="sm"
                :disabled="!link.url"
                v-html="link.label"
            />
        </Link>
    </div>
</template>
