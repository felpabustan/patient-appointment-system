<script lang="ts" setup>
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem, type Patient } from '@/types'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableHead, TableHeader, TableBody, TableRow, TableCell } from '@/components/ui/table'
import { Button } from '@/components/ui/button'
import { Pencil, Delete, UserPlus } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import { capitalizeFirstLetter, formatDateToLong } from '@/helpers'

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Patients',
    href: '/patients',
  },
]

interface PaginatedPatients {
  data: Patient[]
  links: Array<{
    url: string | null
    label: string
    active: boolean
  }>
  meta: {
    current_page: number
    from: number
    last_page: number
    links: Array<{
      url: string | null
      label: string
      active: boolean
    }>
    path: string
    per_page: number
    to: number
    total: number
  }
}

const props = defineProps<{
  patients: PaginatedPatients
}>()

function goToPage(url: string | null): void {
  if (url) router.visit(url)
}

function goToCreate(): void {
  router.visit('/patients/create')
}

function goToEdit(id: number): void {
  router.visit(`/patients/${id}/edit`)
}

function deletePatient(id: number): void {
  toast('Are you sure you want to delete patient profile', {
    action: {
      label: 'Delete',
      onClick: () => handleDelete(id)
    },
    cancel: {
      label: 'Cancel',
      onClick: () => toast.dismiss()
    },
    duration: Infinity
  })
}

function handleDelete(id: number): void {
  router.delete(route('patients.destroy', id), {
    preserveScroll: true,
    onSuccess: () => {
      toast.dismiss()
      toast.warning('Patient profile deleted')
    },
    onError: () => {
      toast.dismiss()
      toast.error('Failed to delete patient profile', {
        style: { background: 'red', color: 'white' },
        action: {
          label: 'Try Again',
          onClick: () => deletePatient(id)
        }
      })
    }
  })
}
</script>

<template>
  <Head title="Patients" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
        <Card>
          <CardHeader class="flex items-center justify-between">
            <CardTitle>Patients</CardTitle>
            <Button @click="goToCreate">
              <UserPlus class="mr-2" /> Add Patient
            </Button>
          </CardHeader>
          <CardContent>
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Name</TableHead>
                  <TableHead>Email</TableHead>
                  <TableHead>Gender</TableHead>
                  <TableHead>Date of Birth</TableHead>
                  <TableHead>Phone</TableHead>
                  <TableHead>Address</TableHead>
                  <TableHead class="text-right">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="patient in patients.data" :key="patient.id">
                  <TableCell>{{ patient.user?.name ?? 'N/A' }}</TableCell>
                  <TableCell>{{ patient.user?.email ?? 'N/A' }}</TableCell>
                  <TableCell>{{ capitalizeFirstLetter(patient.gender) }}</TableCell>
                  <TableCell>{{ formatDateToLong(patient.dob) }}</TableCell>
                  <TableCell>{{ patient.phone }}</TableCell>
                  <TableCell>{{ patient.address }}</TableCell>
                  <TableCell class="text-right">
                    <Button 
                      variant="outline" 
                      size="sm" 
                      @click="goToEdit(patient.id)" 
                      class="mx-2"
                    >
                      <Pencil class="mr-2" /> Edit
                    </Button>
                    <Button 
                      variant="destructive" 
                      size="sm" 
                      @click="deletePatient(patient.id)"
                    >
                      <Delete class="mr-2" /> Delete
                    </Button>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>

            <div class="mt-4 flex flex-wrap gap-2">
              <button
                v-for="link in patients.links"
                :key="link.label"
                :disabled="!link.url"
                @click="goToPage(link.url)"
                v-html="link.label"
                class="rounded border px-3 py-1 text-sm"
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
