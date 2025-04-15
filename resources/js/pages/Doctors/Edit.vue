<script lang="ts" setup>
import { Head, router } from '@inertiajs/vue3'
import { toast } from 'vue-sonner'
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import DoctorForm from './Partials/DoctorForm.vue'
import type { 
  BreadcrumbItem, 
  User, 
  Doctor,
  DoctorFormData 
} from '@/types'

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Doctors',
    href: '/doctors',
  },
  {
    title: 'Edit Doctor',
    href: '#',
  },
]

const props = defineProps<{
  users: User[]
  doctor: Doctor
}>()

const form = ref<DoctorFormData>({
  id: props.doctor.id,
  user_id: props.doctor.user_id ?? null,
  specialty: props.doctor.specialty,
  phone: props.doctor.phone,
  user: props.doctor.user
})

function submit() {
  router.put(`/doctors/${props.doctor.id}`, form.value, {
    onSuccess: () => {
      toast.success('Doctor updated successfully')
    },
    onError: (errors) => {
      toast.error('Failed to update doctor', {
        style: { background: 'red', color: 'white' },
        description: Object.values(errors).flat().join('\n')
      })
    }
  })
}
</script>

<template>
  <Head title="Edit Doctor" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Edit Doctor</h1>
      <DoctorForm
        :form="form"
        :users="users"
        @update:form="form = $event"
        @submit="submit"
      />
    </div>
  </AppLayout>
</template>
