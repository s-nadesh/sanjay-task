<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, usePage } from '@inertiajs/vue3'

const { users } = defineProps({
  users: Object, // paginated top-level categories
})

const page = usePage()
const successMessage = page.props.flash?.success
console.log(successMessage);
</script>

<template>
    <AdminLayout title="Users">
        <div v-if="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ successMessage }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <div class="card">
            <div class="card-body">
                <Link class="btn btn-success" mb-3 :href="route('users.create')">
                    + Add User
                </Link>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in users.data" :key="user.id">
                            <td>{{ index + 1 }}</td>
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ user.roles[0]?.name }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-end">
                    <li
                        v-for="link in users.links"
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
