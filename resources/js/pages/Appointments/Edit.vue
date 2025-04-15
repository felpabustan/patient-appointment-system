<script lang="ts" setup>
import { Head, router } from '@inertiajs/vue3'
import { toast } from 'vue-sonner'
import { ref, computed } from 'vue'
import { today, getLocalTimeZone, parseDate } from '@internationalized/date'
import AppLayout from '@/layouts/AppLayout.vue'
import AppointmentForm from './Partials/AppointmentForm.vue'
import type { 
  BreadcrumbItem, 
  User, 
  AppointmentFormData,
  ExistingAppointment,
  AppointmentDisabledFields 
} from '@/types'

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

const props = defineProps<{
  doctors: User[]
  patients: User[]
  statuses: string[]
  appointment: AppointmentFormData
  userRole: string
  existingAppointments: ExistingAppointment[]
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

// Function to determine which fields should be disabled
const disabledFields = computed<AppointmentDisabledFields>(() => {
  if (props.userRole === 'doctor') {
    return {
      doctor: true,
      patient: true,
      date: true,
      timeSlot: true,
      status: false,
      notes: false
    }
  }
  return {
    doctor: false,
    patient: false,
    date: false,
    timeSlot: false,
    status: false,
    notes: false
  }
})

const form = ref<AppointmentFormData>({
  id: props.appointment.id,
  doctor_id: props.appointment.doctor_id,
  patient_id: props.appointment.patient_id,
  date: ensureValidDate(props.appointment.date),
  time_slot: props.appointment.time_slot,
  status: props.appointment.status,
  notes: props.appointment.notes,
  doctor: props.appointment.doctor,
  patient: props.appointment.patient
})

function submit() {
  // If user is a doctor, only send status and notes
  const dataToSubmit = props.userRole === 'doctor' 
    ? {
        id: form.value.id,
        status: form.value.status,
        notes: form.value.notes,
        // Include these to maintain the existing values
        doctor_id: form.value.doctor_id,
        patient_id: form.value.patient_id,
        date: form.value.date,
        time_slot: form.value.time_slot,
      }
    : form.value

  router.put(`/appointments/${props.appointment.id}`, dataToSubmit, {
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
        :disabled-fields="disabledFields"
        :existing-appointments="existingAppointments"
        @update:form="form = $event"
        @submit="submit"
      />
    </div>
  </AppLayout>
</template>
