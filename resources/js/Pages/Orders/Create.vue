<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { router, usePage } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import axios from 'axios'

// Props from backend
const { props } = usePage()
const errors = ref({})

defineProps({
    users: Array,
    categories: Array,
})

// Form state
const form = ref({
    user_id: '',
    discount: 0,
    items: [],
})

// Add new product row
const addItem = () => {
    form.value.items.push({
        category_id: '',
        product_id: '',
        quantity: 1,
        price: 0,
        products: [],
    })
}

// Remove product row
const removeItem = (index) => {
    form.value.items.splice(index, 1)

    // Reset discount if no items left
    if (form.value.items.length === 0) {
        form.value.discount = 0
    }
}

// Fetch products by category
const fetchProducts = async (item) => {
    if (!item.category_id) {
        item.products = []
        item.product_id = ''
        item.price = 0
        return
    }

    const res = await axios.get(
        route('categories.products', item.category_id)
    )
    item.products = res.data
    item.product_id = ''
    item.price = 0
}

// Set price when product is selected
const setPrice = (item) => {
    const product = item.products.find(p => p.id === item.product_id)
    item.price = product ? product.price : 0
}

// Computed totals
const subTotal = computed(() =>
    form.value.items.reduce(
        (sum, item) => sum + item.price * item.quantity,
        0
    )
)

const finalAmount = computed(() =>
    Math.max(subTotal.value - form.value.discount, 0)
)

// Submit form
const submit = () => {
    const payload = {
        user_id: form.value.user_id,
        discount: form.value.discount,
        items: form.value.items.map(item => ({
            category_id: item.category_id,
            product_id: item.product_id,
            quantity: item.quantity,
            price: item.price,
        })),
    }

    router.post(route('orders.store'), payload, {
        onError: (e) => {
            errors.value = e
        }
    })
}

</script>

<template>
<AdminLayout title="Create Order">
    <div class="card">

        <!-- User Selection -->
        <div class="card-body">
            <div class="form-group mb-3">
                <label>
                    User <span class="text-danger">*</span>
                </label>
                <select v-model="form.user_id" class="form-control">
                    <option value="">Select User</option>
                    <option v-for="u in users" :key="u.id" :value="u.id">
                        {{ u.name }}
                    </option>
                </select>
                <div class="text-danger">{{ errors.user_id }}</div>
            </div>

            <button class="btn btn-primary" @click="addItem">
                + Add Product
            </button>
        </div>

        <!-- Order Items Table -->
        <div class="card-body">
            <div v-if="errors.items" class="alert alert-danger">
                {{ errors.items }}
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Category <span class="text-danger">*</span></th>
                        <th>Product <span class="text-danger">*</span></th>
                        <th width="100">Qty <span class="text-danger">*</span></th>
                        <th width="120">Price</th>
                        <th width="120">Total</th>
                        <th width="60">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in form.items" :key="index">
                        <!-- Category -->
                        <td>
                            <select v-model="item.category_id"
                                    class="form-control"
                                    @change="fetchProducts(item)">
                                <option value="">Select</option>
                                <option v-for="c in categories" :key="c.id" :value="c.id">
                                    {{ c.name }}
                                </option>
                            </select>
                            <span v-if="errors[`items.${index}.category_id`]" class="text-danger text-sm">
                                {{ errors[`items.${index}.category_id`] }}
                            </span>
                        </td>

                        <!-- Product -->
                        <td>
                            <select v-model="item.product_id"
                                    class="form-control"
                                    @change="setPrice(item)">
                                <option value="">Select Product</option>
                                <option v-for="p in item.products" :key="p.id" :value="p.id">
                                    {{ p.name }} ({{ p.price }})
                                </option>
                            </select>
                            <span v-if="errors[`items.${index}.product_id`]" class="text-danger text-sm">
                                {{ errors[`items.${index}.product_id`] }}
                            </span>
                        </td>

                        <!-- Quantity -->
                        <td>
                            <input
                                type="text"
                                class="form-control"
                                v-model="item.quantity"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                @input="item.quantity = item.quantity.replace(/[^0-9]/g, '')"
                            >
                            <span v-if="errors[`items.${index}.quantity`]" class="text-danger text-sm">
                                {{ errors[`items.${index}.quantity`] }}
                            </span>
                        </td>

                        <td>{{ item.price }}</td>
                        <td>{{ item.price * item.quantity }}</td>

                        <td>
                            <button class="btn btn-sm btn-danger" @click="removeItem(index)">
                                ✕
                            </button>
                        </td>
                    </tr>

                    <tr v-if="form.items.length === 0">
                        <td colspan="6" class="text-center text-muted">
                            No products added yet
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Totals & Submit -->
        <div class="card-footer">
            <div class="row">
                <div class="col-md-4 offset-md-8">
                    <table class="table table-bordered">
                        <tr>
                            <th>Sub Total</th>
                            <td>{{ subTotal }}</td>
                        </tr>
                        <tr>
                            <th>Discount</th>
                            <td>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.discount"
                                    inputmode="numeric"
                                    pattern="[0-9]*"
                                    @input="form.discount = form.discount.replace(/[^0-9]/g, '')"
                                >
                                <span v-if="errors.discount" class="text-danger text-sm">
                                    {{ errors.discount }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Final Amount</th>
                            <td class="fw-bold">{{ finalAmount }}</td>
                        </tr>
                    </table>

                    <button class="btn btn-success w-100"
                            @click="submit">
                        Place Order
                    </button>
                </div>
            </div>
        </div>

    </div>
</AdminLayout>
</template>

<style scoped>
.text-sm {
    font-size: 0.85rem;
}
</style>