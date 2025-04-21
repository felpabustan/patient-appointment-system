<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { CalendarCheck, Stethoscope, UserRound, CheckCircle } from 'lucide-vue-next';

interface Stats {
    confirmedAppointments: number;
    totalDoctors?: number;
    totalPatients: number;
    completedAppointments?: number;
}

interface Props {
    stats?: Stats;
}

const props = defineProps<Props>();
const { props: pageProps } = usePage<SharedData>();
const isDoctor = pageProps.auth.user.role === 'doctor';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-white/80 dark:bg-black/80">
                        <CalendarCheck class="h-8 w-8 text-emerald-600 dark:text-emerald-400" />
                        <h3 class="mt-2 text-xl font-semibold text-emerald-700 dark:text-emerald-300">
                            {{ stats?.confirmedAppointments ?? 0 }}
                        </h3>
                        <p class="text-sm text-emerald-600 dark:text-emerald-400">
                            {{ isDoctor ? 'My Confirmed Appointments' : 'Confirmed Appointments' }}
                        </p>
                    </div>
                </div>

                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-white/80 dark:bg-black/80">
                        <component 
                            :is="isDoctor ? CheckCircle : Stethoscope" 
                            class="h-8 w-8 text-blue-600 dark:text-blue-400" 
                        />
                        <h3 class="mt-2 text-xl font-semibold text-blue-700 dark:text-blue-300">
                            {{ isDoctor ? (stats?.completedAppointments ?? 0) : (stats?.totalDoctors ?? 0) }}
                        </h3>
                        <p class="text-sm text-blue-600 dark:text-blue-400">
                            {{ isDoctor ? 'Completed Appointments' : 'Total Doctors' }}
                        </p>
                    </div>
                </div>

                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-white/80 dark:bg-black/80">
                        <UserRound class="h-8 w-8 text-purple-600 dark:text-purple-400" />
                        <h3 class="mt-2 text-xl font-semibold text-purple-700 dark:text-purple-300">
                            {{ stats?.totalPatients ?? 0 }}
                        </h3>
                        <p class="text-sm text-purple-600 dark:text-purple-400">
                            {{ isDoctor ? 'My Patients' : 'Total Patients' }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
            </div>
        </div>
    </AppLayout>
</template>