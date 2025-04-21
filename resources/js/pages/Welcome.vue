<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { 
    CalendarCheck, 
    Clock, 
    Users, 
    Bell,
    Smartphone,
    Settings
} from 'lucide-vue-next';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';

const config = {
    appName: import.meta.env.VITE_APP_NAME || 'Appointment System',
    hero: {
        title: 'Simplify Your Scheduling',
        subtitle: 'An efficient appointment management solution designed to streamline your business operations.',
        primaryCta: 'Get Started',
        secondaryCta: 'Learn More'
    },
    features: [
        {
            icon: Clock,
            title: 'Simple Booking',
            description: 'Streamlined appointment scheduling process for you and your clients.'
        },
        {
            icon: Users,
            title: 'Client Management',
            description: 'Comprehensive tools to manage your client relationships effectively.'
        },
        {
            icon: Bell,
            title: 'Smart Notifications',
            description: 'Automated reminders and updates to keep everyone informed.'
        },
        {
            icon: Smartphone,
            title: 'Mobile Friendly',
            description: 'Access your schedule from any device, anywhere, anytime.'
        },
        {
            icon: Settings,
            title: 'Customizable',
            description: 'Adapt the system to match your specific business needs.'
        },
        {
            icon: CalendarCheck,
            title: 'Calendar Sync',
            description: 'Seamlessly integrate with your existing calendar systems.'
        }
    ]
};
</script>

<template>
    <Head :title="config.appName">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="flex min-h-screen flex-col bg-background">
        <!-- Navigation -->
        <header class="border-b">
            <nav class="mx-auto flex max-w-7xl items-center justify-between p-6">
                <div class="flex items-center gap-2">
                    <CalendarCheck class="h-6 w-6 text-primary" />
                    <span class="text-xl font-semibold">{{ config.appName }}</span>
                </div>
                <div class="flex items-center gap-4">
                    <template v-if="usePage().props.auth.user">
                        <Button asChild variant="outline">
                            <Link :href="route('dashboard')">Dashboard</Link>
                        </Button>
                    </template>
                    <template v-else>
                        <Button asChild variant="ghost">
                            <Link :href="route('login')">Sign in</Link>
                        </Button>
                        <Button asChild>
                            <Link :href="route('register')">{{ config.hero.primaryCta }}</Link>
                        </Button>
                    </template>
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="relative isolate">
            <div class="mx-auto max-w-7xl px-6 py-24 sm:py-32">
                <div class="text-center">
                    <h1 class="text-4xl font-bold tracking-tight sm:text-6xl">
                        {{ config.hero.title }}
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-muted-foreground max-w-2xl mx-auto">
                        {{ config.hero.subtitle }}
                    </p>
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <Button asChild size="lg">
                            <Link :href="route('register')">{{ config.hero.primaryCta }}</Link>
                        </Button>
                        <Button asChild variant="outline" size="lg">
                            <Link href="#features">{{ config.hero.secondaryCta }}</Link>
                        </Button>
                    </div>
                </div>
            </div>
            <!-- Background Pattern -->
            <div class="absolute inset-0 -z-10 opacity-50">
                <PlaceholderPattern />
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="border-t bg-muted/50 py-24">
            <div class="mx-auto max-w-7xl px-6">
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <Card v-for="feature in config.features" :key="feature.title" class="border-0 shadow-sm">
                        <CardContent class="pt-6">
                            <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                                <component :is="feature.icon" class="h-6 w-6 text-primary" />
                            </div>
                            <h3 class="font-semibold">{{ feature.title }}</h3>
                            <p class="mt-2 text-muted-foreground">
                                {{ feature.description }}
                            </p>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t">
            <div class="mx-auto max-w-7xl px-6 py-12">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <CalendarCheck class="h-6 w-6 text-primary" />
                        <span class="font-semibold">{{ config.appName }}</span>
                    </div>
                    <p class="text-sm text-muted-foreground">
                        © {{ new Date().getFullYear() }} {{ config.appName }}. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>