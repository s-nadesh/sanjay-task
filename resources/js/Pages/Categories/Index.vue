<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, usePage } from '@inertiajs/vue3'
import CategoryRow from './CategoryRow.vue'

const { categories } = defineProps({
  categories: Object, // paginated top-level categories
})
const page = usePage()
const successMessage = page.props.flash?.success
</script>

<template>
  <AdminLayout title="Categories">
    <div v-if="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ successMessage }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <div class="card">
      <div class="card-body">
        <Link class="btn btn-success" mb-3 :href="route('categories.create')">
          + Create Category
        </Link>
      </div>

        <div class="card-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Description</th>
                <th>Image</th>
                <th>Status</th>
                <th width="150">Action</th>
              </tr>
            </thead>
            <tbody>
              <template v-for="(category, index) in categories.data" :key="category.id">
                <CategoryRow :category="category" :level="0" :index="index + 1" />
              </template>
            </tbody>
          </table>
        </div>
      

      <!-- Pagination -->
      <div class="card-footer clearfix">
          <ul class="pagination pagination-sm m-0 float-end">
              <li
                  v-for="link in categories.links"
                  :key="link.label"
                  class="page-item"
                  :class="{ active: link.active, disabled: !link.url }"
              >
                  <Link
                      class="page-link"
                      :href="link.url ?? ''"
                      v-html="link.label"
                  />
              </li>
          </ul>
      </div>
    </div>
  </AdminLayout>
</template>

