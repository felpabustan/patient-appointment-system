<script lang="ts" setup>
import { Head, router } from '@inertiajs/vue3'
import { toast } from 'vue-sonner'
import { ref } from 'vue'
import { type BreadcrumbItem, type User, type PatientFormData } from '@/types'
import AppLayout from '@/layouts/AppLayout.vue'
import PatientForm from './Partials/PatientForm.vue'

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Patients',
    href: '/patients',
  },
  {
    title: 'Add Patient',
    href: '/patients/create',
  },
]

const props = defineProps<{
  users: User[]
}>()

const form = ref<PatientFormData>({
  user_id: null,
  gender: '',
  dob: '',
  phone: '',
  address: '',
})

function submit() {
  router.post('/patients', form.value, {
    onSuccess: () => {
      toast.success('Patient created successfully')
    },
    onError: (errors) => {
      toast.error('Failed to create patient', {
        style: { background: 'red', color: 'white' },
        description: Object.values(errors).flat().join('\n')
      })
    }
  })
}
</script>

<template>
  <Head title="Create Patient" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Add Patient</h1>
      <PatientForm
        :form="form"
        :users="users"
        @update:form="(newForm) => form = newForm"
        @submit="submit"
      />
    </div>
  </AppLayout>
</template>
