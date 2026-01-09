<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, usePage } from '@inertiajs/vue3'

defineProps({
    orders: Object,
})

const page = usePage()

const permissions = page.props.auth.permissions || []
const can = (permission) => permissions.includes(permission)

const successMessage = page.props.flash?.success
</script>
<template>
    <AdminLayout title="Orders">
        <div v-if="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ successMessage }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <div class="card">
            <div class="card-body">
                <Link class="btn btn-success" mb-3 :href="route('orders.create')">
                    + Create Order
                </Link>                
                
            </div>

            <!-- Orders Table -->
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Products</th>
                            <th>Quantity</th>
                            <th>Sub Total</th>
                            <th>Discount</th>
                            <th>Final Amount</th>
                            <th>Created At</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="(order, index) in orders.data" :key="order.id">
                            <td>{{ index + 1 }}</td>
                            <td>{{ order.user.name }}</td>

                            <!-- Product Names -->
                            <td>
                                <ul class="list-unstyled mb-0">
                                    <li
                                        v-for="item in order.items"
                                        :key="item.id"
                                    >
                                        {{ item.product.name }}
                                        <small class="text-muted">
                                            (x{{ item.quantity }})
                                        </small>
                                    </li>
                                </ul>
                            </td>
                            <td class="text-center fw-bold">
                                {{
                                    order.items.reduce(
                                        (sum, item) => sum + item.quantity,
                                        0
                                    )
                                }}
                            </td>
                            <td>{{ order.sub_total }}</td>
                            <td>{{ order.discount }}</td>
                            <td class="fw-bold">{{ order.final_amount }}</td>
                            <td>
                                {{ new Date(order.created_at).toLocaleDateString() }}
                            </td>
                            <td>
                                <Link
                                    v-if="can('edit orders')"
                                    :href="route('orders.edit', order.id)"
                                    class="btn btn-sm btn-warning me-1"
                                    title="Edit Order"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                </Link>

                                <a
                                    v-if="can('view orders')"
                                    :href="route('orders.invoice', order.id)"
                                    class="btn btn-sm btn-danger"
                                    title="Download Invoice"
                                    target="_blank"
                                >
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>
                            </td>
                        </tr>

                        <tr v-if="orders.data.length === 0">
                            <td colspan="7" class="text-center text-muted">
                                No orders found
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-end">
                    <li
                        v-for="link in orders.links"
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

