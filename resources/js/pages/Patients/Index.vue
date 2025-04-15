<script lang="ts" setup>
import { defineProps } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableHead, TableHeader, TableBody, TableRow, TableCell } from '@/components/ui/table'
import { Button } from '@/components/ui/button'
import { Pencil, Delete, UserPlus  } from 'lucide-vue-next';
import { toast } from 'vue-sonner'

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Patients',
    href: '/patients',
  },
]

interface Patient {
  id: number
  dob: string
  gender: string
  address: string
  phone: string
  user: {
    name: string
    email: string
  }
}

const props = defineProps<{
  patients: {
    data: Patient[]
    links: Array<{
      url: string | null
      label: string
      active: boolean
    }>
    meta: any
  }
}>()

function goToPage(url: string | null) {
  if (url) router.visit(url)
}

function goToCreate() {
  router.visit('/patients/create')
}

function goToEdit(id: number) {
  router.visit(`/patients/${id}/edit`)
}

function deletePatient(id: number) {
  toast('Are you sure you want to delete patient profile', {
    action: {
      label: 'Delete',
      onClick: () => {
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
    },
    cancel: {
      label: 'Cancel',
      onClick: () => toast.dismiss()
    },
    duration: Infinity
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
              <UserPlus /> Add Patient
            </Button>
          </CardHeader>
          <CardContent>
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Name</TableHead>
                  <TableHead>Email</TableHead>
                  <TableHead>Gender</TableHead>
                  <TableHead>DOB</TableHead>
                  <TableHead>Phone</TableHead>
                  <TableHead>Address</TableHead>
                  <TableHead class="text-right">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="patient in patients.data" :key="patient.id">
                  <TableCell>{{ patient.user?.name ?? 'N/A' }}</TableCell>
                  <TableCell>{{ patient.user?.email ?? 'N/A' }}</TableCell>
                  <TableCell>{{ patient.gender }}</TableCell>
                  <TableCell>{{ patient.dob }}</TableCell>
                  <TableCell>{{ patient.phone }}</TableCell>
                  <TableCell>{{ patient.address }}</TableCell>
                  <TableCell class="text-right">
                    <Button variant="outline" size="sm" @click="goToEdit(patient.id)" class="mx-2">
                      <Pencil /> Edit
                    </Button>
                    <Button variant="destructive" size="sm" @click="deletePatient(patient.id)">
                      <Delete /> Delete
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
