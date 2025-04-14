<script lang="ts" setup>
import { defineProps, defineEmits, computed, onMounted } from 'vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Button } from '@/components/ui/button'
import {
  Select,
  SelectItem,
  SelectContent,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'

const props = defineProps<{
  form: {
    user_id: number | null
    specialty: string
    phone: string
  }
  users: {
    id: number
    name: string
    email: string
  }[]
}>()

const emit = defineEmits(['update:form', 'submit'])

function updateField(field: string, value: any) {
  const newValue = field === 'user_id' ? (value !== null ? Number(value) : null) : value
  emit('update:form', { ...props.form, [field]: newValue })
}

const selectedUser = computed(() => {
  const uid = Number(props.form.user_id)
  return props.users.find(user => user.id === uid)
})

const emailValue = computed(() => selectedUser.value?.email || '')
</script>

<template>
  <form @submit.prevent="emit('submit')" class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
      <div class="w-64">
        <Label for="user">User</Label>
        <div class="mt-2">
          <Select
            :modelValue="form.user_id"
            @update:modelValue="value => updateField('user_id', value)"
          >
            <SelectTrigger id="user" class="w-full">
              <SelectValue :placeholder="'Select a user'" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem v-for="user in users" :key="user.id" :value="user.id">
                {{ user.name }}
              </SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      <div>
        <Label>Email</Label>
        <Input
          v-model="emailValue"
          readonly
          class="mt-2"
          :placeholder="emailValue || 'No email available'"
        />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <Label for="specialty">Specialty</Label>
        <Input
          id="specialty"
          v-model="form.specialty"
          class="mt-2"
          placeholder="Enter specialty"
        />
      </div>

      <div>
        <Label for="phone">Phone</Label>
        <Input
          id="phone"
          v-model="form.phone"
          class="mt-2"
          placeholder="Enter phone"
        />
      </div>
    </div>

    <div>
      <Button type="submit">Submit</Button>
    </div>
  </form>
</template>
