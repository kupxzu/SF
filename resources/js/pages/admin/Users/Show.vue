<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { index, edit } from '@/routes/admin/users';

const props = defineProps<{
    user: {
        id: number;
        name: string;
        username: string;
        email: string;
        role: string;
        created_at: string;
        updated_at: string;
    };
}>();

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <AdminLayout>
        <Head :title="`User: ${user.name}`" />

        <div class="max-w-8xl mx-auto px-6 py-10 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">{{ user.name }}</h1>
                    <p class="text-muted-foreground">@{{ user.username }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <Link :href="edit.url(user.id)">
                        <Button variant="outline">Edit User</Button>
                    </Link>
                    <Link :href="index.url()">
                        <Button variant="outline">Back to Users</Button>
                    </Link>
                </div>
            </div>

            <Card class="max-w-2xl">
                <CardHeader>
                    <CardTitle>User Details</CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <p class="text-xs font-semibold text-muted-foreground uppercase">Full Name</p>
                            <p class="text-sm font-medium mt-1">{{ user.name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-muted-foreground uppercase">Username</p>
                            <p class="text-sm font-medium mt-1">{{ user.username }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-muted-foreground uppercase">Email</p>
                        <p class="text-sm font-medium mt-1">{{ user.email }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-muted-foreground uppercase">Role</p>
                        <span 
                            class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize mt-1"
                            :class="user.role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'"
                        >
                            {{ user.role }}
                        </span>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 pt-4 border-t">
                        <div>
                            <p class="text-xs font-semibold text-muted-foreground uppercase">Created</p>
                            <p class="text-sm font-medium mt-1">{{ formatDate(user.created_at) }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-muted-foreground uppercase">Last Updated</p>
                            <p class="text-sm font-medium mt-1">{{ formatDate(user.updated_at) }}</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>
