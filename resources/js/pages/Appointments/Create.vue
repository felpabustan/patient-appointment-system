
<script lang="ts" setup>
import { Head, router } from '@inertiajs/vue3'
import { toast } from 'vue-sonner'
import { type BreadcrumbItem } from '@/types'
import AppLayout from '@/layouts/AppLayout.vue'
import AppointmentForm from './Partials/AppointmentForm.vue'
import { ref } from 'vue'
import { today, getLocalTimeZone } from '@internationalized/date'

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Appointments',
    href: '/appointments',
  },
  {
    title: 'Create Appointment',
    href: '#',
  },
]

interface User {
  id: number
  name: string
  email: string
}

interface AppointmentFormData {
  doctor_id: number | null
  patient_id: number | null
  date: string
  time_slot: string
  status: string
  notes: string
  doctor?: {
    id: number
    name: string
    email: string
  }
  patient?: {
    id: number
    name: string
    email: string
  }
}

const props = defineProps<{
  doctors: User[]
  patients: User[]
  statuses: string[]
}>()

// Get today's date in YYYY-MM-DD format
const todayDate = today(getLocalTimeZone())
const formattedToday = `${todayDate.year}-${String(todayDate.month).padStart(2, '0')}-${String(todayDate.day).padStart(2, '0')}`

const form = ref<AppointmentFormData>({
  doctor_id: null,
  patient_id: null,
  date: formattedToday,
  time_slot: '09:00',
  status: 'pending',
  notes: '',
})

function submit() {
  router.post('/appointments', form.value, {
    onSuccess: () => {
      toast.success('Appointment created successfully')
    },
    onError: (errors) => {
      toast.error('Failed to create appointment', {
        style: { background: 'red', color: 'white' },
        description: Object.values(errors).flat().join('\n')
      })
    }
  })
}
</script>

<template>
  <Head title="Create Appointment" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-2xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Create Appointment</h1>
      <AppointmentForm
        :form="form"
        :doctors="doctors"
        :patients="patients"
        :statuses="statuses"
        @update:form="form = $event"
        @submit="submit"
      />
    </div>
  </AppLayout>
</template>

