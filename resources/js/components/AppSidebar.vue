<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { LayoutGrid, Users, BriefcaseMedical, PersonStanding, CalendarHeart   } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage(); 
const userRole = props.auth.user.role; 

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    
    ...(userRole === 'admin'
        ? [
            {
                title: 'Users',
                href: '/users',
                icon: Users,
            },
            
            {
                title: 'Doctors',
                href: '/doctors',
                icon: BriefcaseMedical,
            },

            {
                title: 'Patients',
                href: '/patients',
                icon: PersonStanding,
            },

            {
                title: 'Appointments',
                href: '/appointments',
                icon: CalendarHeart,
            },
        ]
    : []),

    ...(userRole == 'doctor' ? [
        {
            title: 'Appointments',
            href: '/appointments',
            icon: CalendarHeart,
        },
    ] : []),
];

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
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
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
