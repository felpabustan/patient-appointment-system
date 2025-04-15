<script lang="ts" setup>
import { defineProps } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableHead, TableHeader, TableBody, TableRow, TableCell } from '@/components/ui/table'
import { Button } from '@/components/ui/button'
import { Pencil, Delete, CalendarPlus } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import { capitalizeFirstLetter, formatDateToLong } from '@/helpers'

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Appointments',
    href: '/appointments',
  },
]

interface Appointment {
  id: number
  date: string
  time_slot: string
  status: 'pending' | 'confirmed' | 'completed' | 'cancelled'
  notes: string | null
  doctor: {
    name: string
    email: string
  }
  patient: {
    name: string
    email: string
  }
}

const props = defineProps<{
  appointments: {
    data: Appointment[]
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
  router.visit('/appointments/create')
}

function goToEdit(id: number) {
  router.visit(`/appointments/${id}/edit`)
}

function deleteAppointment(id: number) {
  toast(
    'Are you sure you want to delete this appointment?',
    {
      action: {
        label: 'Delete',
        onClick: () => {
          router.delete(route('appointments.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
              toast.dismiss()
              toast.warning('Appointment deleted successfully')
            },
            onError: () => {
              toast.dismiss()
              toast.error('Failed to delete appointment', {
                style: { background: 'red', color: 'white' },
                action: {
                  label: 'Try Again',
                  onClick: () => deleteAppointment(id)
                }
              })
            }
          })
        }
      },
      cancel: {
        label: 'Cancel',
        onClick: () => toast.dismiss()
      },
      duration: Infinity
    }
  )
}
</script>

<template>
  <Head title="Appointments" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
        <Card>
          <CardHeader class="flex items-center justify-between">
            <CardTitle>Appointments</CardTitle>
            <Button @click="goToCreate">
              <CalendarPlus class="mr-2" /> New Appointment
            </Button>
          </CardHeader>
          <CardContent>
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Date</TableHead>
                  <TableHead>Time</TableHead>
                  <TableHead>Doctor</TableHead>
                  <TableHead>Patient</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead>Notes</TableHead>
                  <TableHead class="text-right">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="appointment in appointments.data" :key="appointment.id">
                  <TableCell>{{ formatDateToLong(appointment.date) }}</TableCell>
                  <TableCell>{{ appointment.time_slot }}</TableCell>
                  <TableCell>{{ appointment.doctor?.name ?? 'N/A' }}</TableCell>
                  <TableCell>{{ appointment.patient?.name ?? 'N/A' }}</TableCell>
                  <TableCell>
                    <span 
                      class="px-2 py-1 rounded text-sm font-medium"
                      :class="{
                        'bg-yellow-100 text-yellow-800': appointment.status === 'pending',
                        'bg-green-100 text-green-800': appointment.status === 'confirmed',
                        'bg-blue-100 text-blue-800': appointment.status === 'completed',
                        'bg-red-100 text-red-800': appointment.status === 'cancelled'
                      }"
                    >
                      {{ capitalizeFirstLetter(appointment.status) }}
                    </span>
                  </TableCell>
                  <TableCell>{{ appointment.notes ?? '-' }}</TableCell>
                  <TableCell class="text-right">
                    <Button variant="outline" size="sm" @click="goToEdit(appointment.id)" class="mx-2">
                      <Pencil class="mr-2" /> Edit
                    </Button>
                    <Button variant="destructive" size="sm" @click="deleteAppointment(appointment.id)">
                      <Delete class="mr-2" /> Delete
                    </Button>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>

            <div class="mt-4 flex flex-wrap gap-2">
              <button
                v-for="link in appointments.links"
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
