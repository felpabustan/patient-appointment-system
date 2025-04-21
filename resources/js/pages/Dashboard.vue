<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { formatDateToLong } from '@/helpers';
import { 
    CalendarCheck, 
    Stethoscope, 
    UserRound, 
    CheckCircle,
    Clock,
    CalendarX,
    CalendarClock
} from 'lucide-vue-next';

interface Stats {
    confirmedAppointments: number;
    totalDoctors?: number;
    totalPatients: number;
    completedAppointments?: number;
    pendingAppointments: number;
    cancelledAppointments: number;
    todayAppointments: number;
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

const today = formatDateToLong(new Date());
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="text-4xl font-bold text-gray-800 dark:text-gray-200 mb-4">
               Today is {{ today }}
            </div>

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
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min p-6">
                <h2 class="text-2xl font-semibold mb-6">Additional Statistics</h2>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <div class="flex flex-col gap-2 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                        <div class="flex items-center gap-2">
                            <Clock class="h-5 w-5 text-orange-600 dark:text-orange-400" />
                            <h3 class="font-medium text-orange-700 dark:text-orange-300">Pending Appointments</h3>
                        </div>
                        <p class="text-2xl font-semibold text-orange-700 dark:text-orange-300">
                            {{ stats?.pendingAppointments ?? 0 }}
                        </p>
                        <p class="text-sm text-orange-600 dark:text-orange-400">Awaiting confirmation</p>
                    </div>

                    <div class="flex flex-col gap-2 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                        <div class="flex items-center gap-2">
                            <CalendarX class="h-5 w-5 text-red-600 dark:text-red-400" />
                            <h3 class="font-medium text-red-700 dark:text-red-300">Cancelled</h3>
                        </div>
                        <p class="text-2xl font-semibold text-red-700 dark:text-red-300">
                            {{ stats?.cancelledAppointments ?? 0 }}
                        </p>
                        <p class="text-sm text-red-600 dark:text-red-400">Total cancelled appointments</p>
                    </div>

                    <div class="flex flex-col gap-2 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                        <div class="flex items-center gap-2">
                            <CalendarClock class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                            <h3 class="font-medium text-indigo-700 dark:text-indigo-300">{{ isDoctor ? 'Today\'s Schedule' : 'Today\'s Appointments' }}</h3>
                        </div>
                        <p class="text-2xl font-semibold text-indigo-700 dark:text-indigo-300">
                            {{ stats?.todayAppointments ?? 0 }}
                        </p>
                        <p class="text-sm text-indigo-600 dark:text-indigo-400">Scheduled for today</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
