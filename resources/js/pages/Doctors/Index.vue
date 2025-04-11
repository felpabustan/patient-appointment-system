<script lang="ts" setup>
import { defineProps } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableHead, TableHeader, TableBody, TableRow, TableCell } from '@/components/ui/table'
import { Button } from '@/components/ui/button'

interface Doctor {
  id: number
  specialty: string
  user: {
    name: string
    email: string
  }
}

const props = defineProps<{
  doctors: {
    data: Doctor[]
    links: Array<{
      url: string | null
      label: string
      active: boolean
    }>
    meta: any
  }
}>()

function goToPage(url: string | null) {
  if (url) {
    router.visit(url)
  }
}

function goToCreate() {
  router.visit('/doctors/create')
}
</script>

<template>
  <Head title="Doctors" />

  <AppLayout>
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
        <Card>
          <CardHeader>
            <CardTitle>Doctors</CardTitle>
            <Button @click="goToCreate" class="ml-auto">+ Create Doctor</Button>
          </CardHeader>
          <CardContent>
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Name</TableHead>
                  <TableHead>Email</TableHead>
                  <TableHead>Specialty</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="doctor in doctors.data" :key="doctor.id">
                  <TableCell>{{ doctor.user?.name ?? 'N/A' }}</TableCell>
                  <TableCell>{{ doctor.user?.email ?? 'N/A' }}</TableCell>
                  <TableCell>{{ doctor.specialty }}</TableCell>
                </TableRow>
              </TableBody>
            </Table>

            <!-- Pagination -->
            <div class="mt-4 flex flex-wrap gap-2">
              <button
                v-for="link in doctors.links"
                :key="link.label"
                :disabled="!link.url"
                @click="goToPage(link.url)"
                v-html="link.label"
                class="px-3 py-1 rounded border text-sm"
                :class="{
                  'bg-gray-300 dark:bg-gray-700': link.active,
                  'text-gray-500 cursor-not-allowed': !link.url,
                  'hover:bg-gray-200 dark:hover:bg-gray-600': link.url,
                }"
              />
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>
