<script lang="ts" setup>
import { defineProps } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableHead, TableHeader, TableBody, TableRow, TableCell } from '@/components/ui/table'
import { router } from '@inertiajs/vue3'

interface User {
  id: number
  name: string
  email: string
  role: string
}

const props = defineProps<{
  users: {
    data: User[],
    links: any[],
    meta: any
  }
}>()

// Pagination function to handle page navigation
function goToPage(url: string | null) {
  if (url) {
    router.visit(url)
  }
}
</script>

<template>
  <Head title="Users" />

  <AppLayout>
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
        <Card>
          <CardHeader>
            <CardTitle>Users</CardTitle>
          </CardHeader>
          <CardContent>
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Name</TableHead>
                  <TableHead>Email</TableHead>
                  <TableHead>Role</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="user in users.data" :key="user.id">
                  <TableCell>{{ user.name }}</TableCell>
                  <TableCell>{{ user.email }}</TableCell>
                  <TableCell>{{ user.role }}</TableCell>
                </TableRow>
              </TableBody>
            </Table>

            <!-- Pagination -->
            <div class="mt-4 flex gap-2">
              <button
                v-for="link in users.links"
                :key="link.label"
                :disabled="!link.url"
                @click="goToPage(link.url)"
                v-html="link.label"
                class="px-3 py-1 rounded border text-sm"
                :class="{
                  'bg-gray-300 dark:bg-gray-700': link.active,
                  'text-gray-500': !link.url,
                }"
              />
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>
