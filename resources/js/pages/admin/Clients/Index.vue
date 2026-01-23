<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import Pagination from '@/components/Pagination.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { create, edit, destroy, index } from '@/routes/admin/clients';

const props = defineProps<{
    clients: any;
    filters: {
        search: string;
        date_from: string;
        date_to: string;
    };
}>();

const viewMode = ref<'list' | 'grid'>('list');
const search = ref(props.filters.search);
const dateFrom = ref(props.filters.date_from);
const dateTo = ref(props.filters.date_to);

let debounceTimer: ReturnType<typeof setTimeout>;

const applyFilters = () => {
    router.get(index.url(), {
        search: search.value || undefined,
        date_from: dateFrom.value || undefined,
        date_to: dateTo.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

watch(search, () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(applyFilters, 300);
});

const clearFilters = () => {
    search.value = '';
    dateFrom.value = '';
    dateTo.value = '';
    applyFilters();
};
</script>

<template>
    <AdminLayout>
        <Head title="Clients" />

        <div class="max-w-8xl mx-auto px-6 py-10 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Clients</h1>
                    <p class="text-muted-foreground">Manage your clients and their information</p>
                </div>
                <div class="flex items-center gap-3">
                    <!-- View Mode Toggle -->
                    <div class="flex items-center border rounded-lg overflow-hidden">
                        <button 
                            @click="viewMode = 'list'"
                            :class="['px-3 py-2 text-sm font-medium transition-colors', viewMode === 'list' ? 'bg-primary text-primary-foreground' : 'bg-white hover:bg-muted']"
                        >
                            ☰ List
                        </button>
                        <button 
                            @click="viewMode = 'grid'"
                            :class="['px-3 py-2 text-sm font-medium transition-colors', viewMode === 'grid' ? 'bg-primary text-primary-foreground' : 'bg-white hover:bg-muted']"
                        >
                            ▦ Grid
                        </button>
                    </div>
                    <Link :href="create.url()">
                        <Button>Add Client</Button>
                    </Link>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                <div class="flex-1 w-full sm:max-w-sm">
                    <Input 
                        v-model="search" 
                        type="text" 
                        placeholder="Search clients..." 
                        class="w-full"
                    />
                </div>
                <div class="flex flex-wrap gap-2 items-center">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-muted-foreground whitespace-nowrap">From:</span>
                        <input 
                            v-model="dateFrom" 
                            type="date" 
                            class="h-9 rounded-md border border-input bg-background px-3 py-1 text-sm"
                            @change="applyFilters"
                        />
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-muted-foreground whitespace-nowrap">To:</span>
                        <input 
                            v-model="dateTo" 
                            type="date" 
                            class="h-9 rounded-md border border-input bg-background px-3 py-1 text-sm"
                            @change="applyFilters"
                        />
                    </div>
                    <Button 
                        v-if="search || dateFrom || dateTo" 
                        variant="ghost" 
                        size="sm"
                        @click="clearFilters"
                    >
                        Clear
                    </Button>
                </div>
            </div>

            <!-- List View -->
            <div v-if="viewMode === 'list'" class="rounded-lg border bg-white">
                <div class="w-full overflow-x-auto">
                    <table class="min-w-[900px] w-full whitespace-nowrap">
                    <thead class="border-b bg-muted/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Name</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Email & Address</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Phone</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Pets</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="client in clients.data" :key="client.id" class="border-b last:border-0 hover:bg-muted/30">
                            <td class="px-4 py-3 font-medium">{{ client.fullname }}</td>
                            <td class="px-4 py-3">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-foreground">
                                {{ client.email }}
                                </span>
                                <span
                                class="text-xs text-muted-foreground truncate max-w-[260px]"
                                :title="client.address"
                                >
                                {{ client.address }}
                                </span>
                            </div>
                            </td>

                            <td class="px-4 py-3">
                            {{ client.number }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">{{ client.pets_count }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <Link :href="edit.url(client.id)">
                                        <Button variant="outline" size="sm">Edit</Button>
                                    </Link>
                                    <Link :href="destroy.url(client.id)" method="delete" as="button">
                                        <Button variant="destructive" size="sm">Delete</Button>
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>

            <!-- Grid View -->
            <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Card v-for="client in clients.data" :key="client.id" class="hover:shadow-lg transition-shadow">
                    <CardHeader>
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <CardTitle class="text-lg">{{ client.fullname }}</CardTitle>
                                <p class="text-sm text-muted-foreground mt-1 truncate">{{ client.email }}</p>
                            </div>
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">{{ client.pets_count }}</span>
                        </div>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-3 border-t pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-semibold text-muted-foreground uppercase">Phone</span>
                                <span class="text-sm font-medium">{{ client.number }}</span>
                            </div>
                            <div v-if="client.address" class="space-y-1">
                                <span class="text-xs font-semibold text-muted-foreground uppercase">Address</span>
                                <p class="text-sm text-muted-foreground line-clamp-2" :title="client.address">{{ client.address }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2 pt-4 border-t">
                            <Link :href="edit.url(client.id)" class="flex-1">
                                <Button variant="outline" size="sm" class="w-full">Edit</Button>
                            </Link>
                            <Link :href="destroy.url(client.id)" method="delete" as="button" class="flex-1">
                                <Button variant="destructive" size="sm" class="w-full">Delete</Button>
                            </Link>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <Pagination :links="clients.links" />
        </div>
    </AdminLayout>
</template>
