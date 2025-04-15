<script lang="ts" setup>
import { Head, router } from '@inertiajs/vue3'
import { toast } from 'vue-sonner'
import { type BreadcrumbItem } from '@/types'
import AppLayout from '@/layouts/AppLayout.vue'
import AppointmentForm from './Partials/AppointmentForm.vue'
import { ref } from 'vue'
import { today, getLocalTimeZone, parseDate } from '@internationalized/date'

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Appointments',
    href: '/appointments',
  },
  {
    title: 'Edit Appointment',
    href: '#',
  },
]

interface User {
  id: number
  name: string
  email: string
}

interface AppointmentFormData {
  id?: number
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
  appointment: AppointmentFormData
}>()

// Function to ensure date is not in the past
function ensureValidDate(dateStr: string): string {
  try {
    const selectedDate = parseDate(dateStr)
    const todayDate = today(getLocalTimeZone())
    
    // If date is in the past, return today's date formatted as YYYY-MM-DD
    if (selectedDate.compare(todayDate) < 0) {
      return `${todayDate.year}-${String(todayDate.month).padStart(2, '0')}-${String(todayDate.day).padStart(2, '0')}`
    }
    return dateStr
  } catch (e) {
    // If there's any error, return today's date
    const todayDate = today(getLocalTimeZone())
    return `${todayDate.year}-${String(todayDate.month).padStart(2, '0')}-${String(todayDate.day).padStart(2, '0')}`
  }
}

const form = ref<AppointmentFormData>({
  id: props.appointment.id,
  doctor_id: props.appointment.doctor_id,
  patient_id: props.appointment.patient_id,
  date: ensureValidDate(props.appointment.date), // Ensure date is valid
  time_slot: props.appointment.time_slot,
  status: props.appointment.status,
  notes: props.appointment.notes,
  doctor: props.appointment.doctor,
  patient: props.appointment.patient
})

function submit() {
  router.put(`/appointments/${props.appointment.id}`, form.value, {
    onSuccess: () => {
      toast.success('Appointment updated successfully')
    },
    onError: (errors) => {
      toast.error('Failed to update appointment', {
        style: { background: 'red', color: 'white' },
        description: Object.values(errors).flat().join('\n')
      })
    }
  })
}
</script>

<template>
  <Head title="Edit Appointment" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-2xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Edit Appointment</h1>
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
