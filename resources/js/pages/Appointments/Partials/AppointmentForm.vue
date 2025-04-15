<script lang="ts" setup>
import { defineProps, defineEmits, computed } from 'vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Button } from '@/components/ui/button'
import { Textarea } from '@/components/ui/textarea'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import {
  Popover,
  PopoverTrigger,
  PopoverContent,
} from '@/components/ui/popover'
import { Calendar } from '@/components/ui/calendar'
import { CalendarIcon } from 'lucide-vue-next'
import { CalendarDate, getLocalTimeZone, parseDate, today } from '@internationalized/date'
import { cn } from '@/lib/utils'

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

interface User {
  id: number
  name: string
  email: string
}

const props = defineProps<{
  form: AppointmentFormData
  doctors: User[]
  patients: User[]
  statuses: string[]
}>()

const emit = defineEmits<{
  (e: 'update:form', value: AppointmentFormData): void
  (e: 'submit'): void
}>()

const timeSlots = [
  '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
  '14:00', '14:30', '15:00', '15:30', '16:00', '16:30'
]

function updateField(field: string, value: any) {
  const newValue = ['doctor_id', 'patient_id'].includes(field) 
    ? (value !== null ? Number(value) : null) 
    : value
  emit('update:form', { ...props.form, [field]: newValue })
}

const selectedDoctor = computed(() => {
  const did = Number(props.form.doctor_id)
  return props.doctors.find(doctor => doctor.id === did)
})

const selectedPatient = computed(() => {
  const pid = Number(props.form.patient_id)
  return props.patients.find(patient => patient.id === pid)
})

const doctorEmail = computed(() => selectedDoctor.value?.email || '')
const patientEmail = computed(() => selectedPatient.value?.email || '')

const formatDate = (date: CalendarDate | undefined) => {
  if (!date) return 'Pick a date'
  return `${date.year}-${String(date.month).padStart(2, '0')}-${String(date.day).padStart(2, '0')}`
}

const dateValue = computed({
  get: () => {
    if (!props.form.date) return undefined
    try {
      return parseDate(props.form.date)
    } catch (e) {
      console.warn('Invalid date format:', props.form.date)
      return undefined
    }
  },
  set: (val) => {
    emit('update:form', { 
      ...props.form, 
      date: val ? formatDate(val) : '' 
    })
  }
})
</script>

<template>
  <form @submit.prevent="emit('submit')" class="space-y-6">
    <!-- Doctor Selection -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
      <div>
        <Label for="doctor">Doctor</Label>
        <div class="mt-2">
          <Select
            :modelValue="form.doctor_id"
            @update:modelValue="value => updateField('doctor_id', value)"
          >
            <SelectTrigger id="doctor" class="w-full">
              <SelectValue placeholder="Select a doctor" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem
                v-for="doctor in doctors"
                :key="doctor.id"
                :value="doctor.id"
              >
                {{ doctor.name }}
              </SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      <div>
        <Label>Doctor Email</Label>
        <Input
          v-model="doctorEmail"
          readonly
          class="mt-2"
          :placeholder="doctorEmail || 'No email available'"
        />
      </div>
    </div>

    <!-- Patient Selection -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
      <div>
        <Label for="patient">Patient</Label>
        <div class="mt-2">
          <Select
            :modelValue="form.patient_id"
            @update:modelValue="value => updateField('patient_id', value)"
          >
            <SelectTrigger id="patient" class="w-full">
              <SelectValue placeholder="Select a patient" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem
                v-for="patient in patients"
                :key="patient.id"
                :value="patient.id"
              >
                {{ patient.name }}
              </SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      <div>
        <Label>Patient Email</Label>
        <Input
          v-model="patientEmail"
          readonly
          class="mt-2"
          :placeholder="patientEmail || 'No email available'"
        />
      </div>
    </div>

    <!-- Date and Time Selection -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <Label for="date">Date</Label>
        <div class="mt-2">
          <Popover>
            <PopoverTrigger as-child>
              <Button
                variant="outline"
                :class="cn(
                  'w-full text-left font-normal',
                  !dateValue && 'text-muted-foreground'
                )"
              >
                <span>{{ dateValue ? formatDate(dateValue) : 'Pick a date' }}</span>
                <CalendarIcon class="ms-auto h-4 w-4 opacity-50" />
              </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
              <Calendar
                v-model="dateValue"
                calendar-label="Appointment date"
                initial-focus
                :min-value="today(getLocalTimeZone())"
              />
            </PopoverContent>
          </Popover>
        </div>
      </div>

      <div>
        <Label for="time_slot">Time Slot</Label>
        <div class="mt-2">
          <Select
            :modelValue="form.time_slot"
            @update:modelValue="value => updateField('time_slot', value)"
          >
            <SelectTrigger id="time_slot" class="w-full">
              <SelectValue placeholder="Select time" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem
                v-for="time in timeSlots"
                :key="time"
                :value="time"
              >
                {{ time }}
              </SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>
    </div>

    <!-- Status -->
    <div>
      <Label for="status">Status</Label>
      <div class="mt-2">
        <Select
          :modelValue="form.status"
          @update:modelValue="value => updateField('status', value)"
        >
          <SelectTrigger id="status" class="w-full">
            <SelectValue placeholder="Select status" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem
              v-for="status in statuses"
              :key="status"
              :value="status"
            >
              {{ status.charAt(0).toUpperCase() + status.slice(1) }}
            </SelectItem>
          </SelectContent>
        </Select>
      </div>
    </div>

    <!-- Notes -->
    <div>
      <Label for="notes">Notes</Label>
      <div class="mt-2">
        <Textarea
          id="notes"
          v-model="form.notes"
          rows="4"
          placeholder="Add any additional notes here..."
          @input="updateField('notes', $event.target.value)"
        />
      </div>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end">
      <Button type="submit">
        Save Appointment
      </Button>
    </div>
  </form>
</template>
