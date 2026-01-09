<template>
  <tr>
    <td v-if="level === 0">{{ index }}</td>
    <td v-else></td>
    <td>
      <span :style="{ paddingLeft: (level * 20) + 'px' }">
        <span v-if="level > 0">└─ </span>{{ category.name }}
      </span>
    </td>
    
    <td>
      <span :style="{ paddingLeft: (level * 20) + 'px' }">
        {{ category.description}}
      </span>
    </td>
    <td>
      <img v-if="category.image"
          :src="`/storage/${category.image}`"
          width="40" />
    </td>
    <td>
      <span class="badge" :class="category.status ? 'bg-success' : 'bg-danger'">
        {{ category.status ? 'Active' : 'Inactive' }}
      </span>
    </td>
    <td>
      <Link class="btn btn-sm btn-warning" :href="route('categories.edit', category.id)" title="Edit Category">
        <i class="bi bi-pencil-square"></i>
      </Link>
      <button class="btn btn-sm btn-danger ms-1" @click="handleDelete(category.id)" title="Delete Category">
        <i class="bi bi-trash"></i>
      </button>
    </td>
  </tr>

  <!-- Recursive children -->
  <template v-if="category.children_recursive && category.children_recursive.length">
    <CategoryRow
      v-for="child in category.children_recursive"
      :key="child.id"
      :category="child"
      :level="level + 1"
    />
  </template>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import { watchEffect } from 'vue'

// Destructure props
const { category, level, index } = defineProps({
  category: Object,
  level: { type: Number, default: 0 },
  index: Number,
})

// Delete function
const handleDelete = (id) => {
  if (confirm('Are you sure to delete this category?')) {
    console.log('Deleting category ID:', id)
    router.delete(route('categories.destroy', id))
  }
}
</script>
