<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

interface Props {
    stats: {
        total_users: number;
        admin_users: number;
        staff_users: number;
        recent_users: Array<{
            id: number;
            name: string;
            username: string;
            email: string;
            role: string;
            created_at: string;
        }>;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin Dashboard',
        href: '/admin/dashboard',
    },
];
</script>

<template>
    <Head title="Admin Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="grid gap-4 md:grid-cols-3">
                <!-- Total Users Card -->
                <div
                    class="rounded-xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border"
                >
                    <div class="flex flex-col gap-2">
                        <p class="text-sm text-muted-foreground">
                            Total Users
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.total_users }}
                        </p>
                    </div>
                </div>

                <!-- Admin Users Card -->
                <div
                    class="rounded-xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border"
                >
                    <div class="flex flex-col gap-2">
                        <p class="text-sm text-muted-foreground">
                            Admin Users
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.admin_users }}
                        </p>
                    </div>
                </div>

                <!-- Staff Users Card -->
                <div
                    class="rounded-xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border"
                >
                    <div class="flex flex-col gap-2">
                        <p class="text-sm text-muted-foreground">
                            Staff Users
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.staff_users }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Recent Users Table -->
            <div
                class="rounded-xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border"
            >
                <h2 class="mb-4 text-lg font-semibold">Recent Users</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="pb-3 text-left font-medium">Name</th>
                                <th class="pb-3 text-left font-medium">
                                    Username
                                </th>
                                <th class="pb-3 text-left font-medium">
                                    Email
                                </th>
                                <th class="pb-3 text-left font-medium">Role</th>
                                <th class="pb-3 text-left font-medium">
                                    Created
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="user in stats.recent_users"
                                :key="user.id"
                                class="border-b last:border-0"
                            >
                                <td class="py-3">{{ user.name }}</td>
                                <td class="py-3">{{ user.username }}</td>
                                <td class="py-3">{{ user.email }}</td>
                                <td class="py-3">
                                    <span
                                        class="inline-flex rounded-full px-2 py-1 text-xs font-semibold"
                                        :class="{
                                            'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300':
                                                user.role === 'admin',
                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300':
                                                user.role === 'staff',
                                        }"
                                    >
                                        {{ user.role }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    {{
                                        new Date(
                                            user.created_at,
                                        ).toLocaleDateString()
                                    }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <a
                        href="/admin/users"
                        class="text-sm text-primary hover:underline"
                    >
                        View all users →
                    </a>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
