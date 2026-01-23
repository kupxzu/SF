<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { index, update } from '@/routes/admin/users';

const props = defineProps<{
    user: {
        id: number;
        name: string;
        username: string;
        email: string;
        role: string;
    };
}>();

const form = useForm({
    name: props.user.name,
    username: props.user.username,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    role: props.user.role,
});

const submit = () => {
    form.put(update.url(props.user.id));
};
</script>

<template>
    <AdminLayout>
        <Head title="Edit User" />

        <div class="max-w-8xl mx-auto px-6 py-10 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Edit User</h1>
                    <p class="text-muted-foreground">Update user information</p>
                </div>
                <Link :href="index.url()">
                    <Button variant="outline">Back to Users</Button>
                </Link>
            </div>

            <Card class="max-w-2xl">
                <CardHeader>
                    <CardTitle>User Information</CardTitle>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="name">Full Name</Label>
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    placeholder="Enter full name"
                                    :class="{ 'border-red-500': form.errors.name }"
                                />
                                <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
                            </div>

                            <div class="space-y-2">
                                <Label for="username">Username</Label>
                                <Input
                                    id="username"
                                    v-model="form.username"
                                    type="text"
                                    placeholder="Enter username"
                                    :class="{ 'border-red-500': form.errors.username }"
                                />
                                <p v-if="form.errors.username" class="text-sm text-red-500">{{ form.errors.username }}</p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="email">Email</Label>
                            <Input
                                id="email"
                                v-model="form.email"
                                type="email"
                                placeholder="Enter email address"
                                :class="{ 'border-red-500': form.errors.email }"
                            />
                            <p v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label for="role">Role</Label>
                            <select
                                id="role"
                                v-model="form.role"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                :class="{ 'border-red-500': form.errors.role }"
                            >
                                <option value="staff">Staff</option>
                                <option value="admin">Admin</option>
                            </select>
                            <p v-if="form.errors.role" class="text-sm text-red-500">{{ form.errors.role }}</p>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="password">New Password</Label>
                                <Input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    placeholder="Leave blank to keep current"
                                    :class="{ 'border-red-500': form.errors.password }"
                                />
                                <p v-if="form.errors.password" class="text-sm text-red-500">{{ form.errors.password }}</p>
                                <p class="text-xs text-muted-foreground">Leave blank to keep current password</p>
                            </div>

                            <div class="space-y-2">
                                <Label for="password_confirmation">Confirm Password</Label>
                                <Input
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    type="password"
                                    placeholder="Confirm new password"
                                />
                            </div>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Updating...' : 'Update User' }}
                            </Button>
                            <Link :href="index.url()">
                                <Button type="button" variant="outline">Cancel</Button>
                            </Link>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>
