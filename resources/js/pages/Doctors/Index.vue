<script lang="ts" setup>
import { defineProps } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem, type DoctorListResponse } from '@/types'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableHead, TableHeader, TableBody, TableRow, TableCell } from '@/components/ui/table'
import { Button } from '@/components/ui/button'
import { Pencil, Delete, UserPlus } from 'lucide-vue-next'
import { toast } from 'vue-sonner'

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Doctors',
    href: '/doctors',
  },
]

const props = defineProps<{
  doctors: DoctorListResponse
}>()

function goToPage(url: string | null) {
  if (url) {
    router.visit(url)
  }
}

function goToCreate() {
  router.visit('/doctors/create')
}

function goToEdit(id: number) {
  router.visit(`/doctors/${id}/edit`)
}

function deleteDoctor(id: number) {
  toast(
    'Are you sure you want to delete this doctor?',
    {
      action: {
        label: 'Delete',
        onClick: () => {
          router.delete(route('doctors.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
              toast.dismiss()
              toast.warning('Doctor deleted successfully')
            },
            onError: () => {
              toast.dismiss()
              toast.error('Failed to delete doctor', {
                style: { background: 'red', color: 'white' },
                action: {
                  label: 'Try Again',
                  onClick: () => deleteDoctor(id)
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
  <Head title="Doctors" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
        <Card>
          <CardHeader class="flex items-center justify-between">
            <CardTitle>Doctors</CardTitle>
            <Button @click="goToCreate">
              <UserPlus class="mr-2" /> Add Doctor
            </Button>
          </CardHeader>
          <CardContent>
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Name</TableHead>
                  <TableHead>Email</TableHead>
                  <TableHead>Specialty</TableHead>
                  <TableHead>Phone</TableHead>
                  <TableHead class="text-right">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="doctor in doctors.data" :key="doctor.id">
                  <TableCell>{{ doctor.user?.name ?? 'N/A' }}</TableCell>
                  <TableCell>{{ doctor.user?.email ?? 'N/A' }}</TableCell>
                  <TableCell>{{ doctor.specialty }}</TableCell>
                  <TableCell>{{ doctor.phone }}</TableCell>
                  <TableCell class="text-right">
                    <Button variant="outline" size="sm" @click="goToEdit(doctor.id)" class="mx-2">
                      <Pencil class="mr-2" /> Edit
                    </Button>
                    <Button variant="destructive" size="sm" @click="deleteDoctor(doctor.id)">
                      <Delete class="mr-2" /> Delete
                    </Button>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>

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
