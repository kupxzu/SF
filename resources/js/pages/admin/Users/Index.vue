<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import Pagination from '@/components/Pagination.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { create, edit, destroy } from '@/routes/admin/users';

defineProps<{
    users: any;
}>();

const viewMode = ref<'list' | 'grid'>('list');

const getRoleBadgeClass = (role: string) => {
    return role === 'admin' 
        ? 'bg-red-100 text-red-800' 
        : 'bg-blue-100 text-blue-800';
};
</script>

<template>
    <AdminLayout>
        <Head title="Users" />

        <div class="max-w-8xl mx-auto px-6 py-10 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Users</h1>
                    <p class="text-muted-foreground">Manage system users and their roles</p>
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
                        <Button>Add User</Button>
                    </Link>
                </div>
            </div>

            <!-- List View -->
            <div v-if="viewMode === 'list'" class="rounded-lg border bg-white">
                <div class="w-full overflow-x-auto">
                    <table class="min-w-[800px] w-full whitespace-nowrap">
                        <thead class="border-b bg-muted/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Name</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Username</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Email</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold">Role</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Created</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="user in users.data"
                                :key="user.id"
                                class="border-b last:border-0 hover:bg-muted/30"
                            >
                                <td class="px-4 py-3 font-medium">{{ user.name }}</td>
                                <td class="px-4 py-3 text-muted-foreground">{{ user.username }}</td>
                                <td class="px-4 py-3">{{ user.email }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize"
                                        :class="getRoleBadgeClass(user.role)"
                                    >
                                        {{ user.role }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-muted-foreground text-sm">
                                    {{ new Date(user.created_at).toLocaleDateString() }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <Link :href="edit.url(user.id)">
                                            <Button variant="outline" size="sm">Edit</Button>
                                        </Link>
                                        <Link :href="destroy.url(user.id)" method="delete" as="button">
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
                <Card v-for="user in users.data" :key="user.id" class="hover:shadow-lg transition-shadow">
                    <CardHeader>
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <CardTitle class="text-lg">{{ user.name }}</CardTitle>
                                <p class="text-sm text-muted-foreground mt-1">@{{ user.username }}</p>
                            </div>
                            <span
                                class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize"
                                :class="getRoleBadgeClass(user.role)"
                            >
                                {{ user.role }}
                            </span>
                        </div>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-3 border-t pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-semibold text-muted-foreground uppercase">Email</span>
                                <span class="text-sm font-medium truncate max-w-[180px]" :title="user.email">{{ user.email }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-semibold text-muted-foreground uppercase">Created</span>
                                <span class="text-sm font-medium">{{ new Date(user.created_at).toLocaleDateString() }}</span>
                            </div>
                        </div>
                        <div class="flex gap-2 pt-4 border-t">
                            <Link :href="edit.url(user.id)" class="flex-1">
                                <Button variant="outline" size="sm" class="w-full">Edit</Button>
                            </Link>
                            <Link :href="destroy.url(user.id)" method="delete" as="button" class="flex-1">
                                <Button variant="destructive" size="sm" class="w-full">Delete</Button>
                            </Link>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <Pagination :links="users.links" />
        </div>
    </AdminLayout>
</template>
    