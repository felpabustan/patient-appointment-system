<script lang="ts" setup>
import { defineProps, defineEmits, computed } from 'vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Button } from '@/components/ui/button'
import { Select, SelectItem, SelectContent, SelectTrigger, SelectValue } from '@/components/ui/select'

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
  emit('update:form', { ...props.form, [field]: value })
}

const selectedUser = computed(() =>
  props.users.find(user => user.id === props.form.user_id)
)
</script>

<template>
  <form @submit.prevent="emit('submit')" class="space-y-4">

    <!-- User dropdown -->
    <div>
      <Label for="user">User</Label>
      <Select :modelValue="form.user_id" @update:modelValue="val => updateField('user_id', val)">
        <SelectTrigger>
          <SelectValue placeholder="Select a user" />
        </SelectTrigger>
        <SelectContent>
          <SelectItem
            v-for="user in users"
            :key="user.id"
            :value="user.id"
          >
            {{ user.name }}
          </SelectItem>
        </SelectContent>
      </Select>
    </div>

    <!-- Email display (readonly) -->
    <div v-if="selectedUser">
      <Label>Email</Label>
      <Input :value="selectedUser.email" readonly />
    </div>

    <!-- Specialty -->
    <div>
      <Label for="specialty">Specialty</Label>
      <Input id="specialty" v-model="form.specialty" />
    </div>

    <!-- Phone -->
    <div>
      <Label for="phone">Phone</Label>
      <Input id="phone" v-model="form.phone" />
    </div>

    <Button type="submit">Submit</Button>
  </form>
</template>
