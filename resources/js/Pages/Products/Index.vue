<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router, usePage } from '@inertiajs/vue3'

defineProps({
    products: Object,
})

const page = usePage()

const successMessage = page.props.flash?.success

const deleteProduct = (id) => {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete(route('products.destroy', id))
    }
}

function categoryPath(category) {
    let path = category.name
    let parent = category.parent_recursive

    while (parent) {
        path = parent.name + ' > ' + path
        parent = parent.parent_recursive
    }

    return path
}

</script>

<template>
    <AdminLayout title="Products">
        <div v-if="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ successMessage }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <div class="card">
            <div class="card-body">
                <Link class="btn btn-success" mb-3 :href="route('products.create')">
                 + Add Product
                </Link>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(product, index) in products.data" :key="product.id">
                            <td>{{ index + 1 }}</td>
                            <td>{{ product.name }}</td>
                            <td>{{ categoryPath(product.category) }}</td>
                            <td>{{ product.price }}</td>
                            <td>{{ product.description }}</td>
                            <td>
                                <img v-if="product.image"
                                    :src="`/storage/${product.image}`"
                                    width="50" />
                            </td>
                            <td>
                                <span class="badge"
                                      :class="product.status ? 'bg-success' : 'bg-danger'">
                                    {{ product.status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <Link class="btn btn-sm btn-warning"
                                      :href="route('products.edit', product.id)" title="Edit Product">
                                    <i class="bi bi-pencil-square"></i>
                                </Link>
                                <button class="btn btn-sm btn-danger ms-1"
                                        @click="deleteProduct(product.id)" title="Delete Product">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

             <!-- Pagination -->
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-end">
                    <li
                        v-for="link in products.links"
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