<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, Users, PawPrint, UserPlus, Plus } from 'lucide-vue-next';
import { computed } from 'vue';

import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem, type AppPageProps } from '@/types';

import AppLogo from './AppLogo.vue';

const page = usePage<AppPageProps>();
const isAdmin = computed(() => page.props.auth?.user?.role === 'admin');

const mainNavItems = computed((): NavItem[] => {
    const items: NavItem[] = [
        {
            title: 'Dashboard',
            href: dashboard(),
            icon: LayoutGrid,
        },
    ];

    if (isAdmin.value) {
        items.push(
            {
                title: 'Users',
                href: '/admin/users',
                icon: Users,
            },
            {
                title: 'Clients',
                href: '/admin/clients',
                icon: UserPlus,
                items: [
                    {
                        title: 'All Clients',
                        href: '/admin/clients',
                    },
                    {
                        title: 'Add Client',
                        href: '/admin/clients/create',
                    },
                ],
            },
            {
                title: 'Pets',
                href: '/admin/pets',
                icon: PawPrint,
                items: [
                    {
                        title: 'All Pets',
                        href: '/admin/pets',
                    },
                    {
                        title: 'Add Pet',
                        href: '/admin/pets/create',
                    },
                ],
            },
        );
    }

    return items;
});

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
