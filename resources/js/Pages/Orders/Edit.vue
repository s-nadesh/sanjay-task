<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import axios from 'axios'

const { props } = usePage()
defineProps({
    order: Object,
    users: Array,
    categories: Array,
})

// Initialize form using Inertia's useForm for automatic validation handling
const form = useForm({
    user_id: props.order.user_id,
    discount: props.order.discount || 0,
    items: props.order.items.map(i => ({
        category_id: i.category_id,
        product_id: i.product_id,
        quantity: i.quantity,
        price: i.price,
        products: [], // will load dynamically when category changes
    })),
})

// Preload products for existing items
form.items.forEach(async item => {
    if (item.category_id) {
        const res = await axios.get(route('categories.products', item.category_id))
        item.products = res.data
    }
})

// Add new item
const addItem = () => {
    form.items.push({
        category_id: '',
        product_id: '',
        quantity: 1,
        price: 0,
        products: [],
    })
}

// Remove item
const removeItem = (index) => {
    form.items.splice(index, 1)
    if (form.items.length === 0) form.discount = 0
}

// Fetch products for category
const fetchProducts = async item => {
    if (!item.category_id) return
    const res = await axios.get(route('categories.products', item.category_id))
    item.products = res.data

    // Reset product if previous product_id doesn't belong to this category
    if (!item.products.find(p => p.id === item.product_id)) {
        item.product_id = ''
        item.price = 0
    }
}

// Set price when product selected
const setPrice = (item) => {
    const product = item.products.find(p => p.id === item.product_id)
    item.price = product ? product.price : 0
}

// Computed totals
const subTotal = computed(() =>
    form.items.reduce((sum, item) => sum + item.price * item.quantity, 0)
)

const finalAmount = computed(() =>
    Math.max(subTotal.value - form.discount, 0)
)

// Submit form
const submit = () => {
    form.put(route('orders.update', props.order.id), {
        forceFormData: true,
        onError: () => {
            console.log('Validation errors', form.errors)
        }
    })
}
</script>

<template>
<AdminLayout title="Edit Order">
    <div class="card">

        <!-- User Selection -->
        <div class="card-body">
            <div class="form-group mb-3">
                <label>User <span class="text-danger">*</span></label>
                <select v-model="form.user_id" class="form-control">
                    <option value="">Select User</option>
                    <option v-for="u in users" :key="u.id" :value="u.id">{{ u.name }}</option>
                </select>
                <!-- User validation -->
                <span v-if="form.errors.user_id" class="text-danger">{{ form.errors.user_id }}</span>
            </div>

            <button class="btn btn-primary mb-3" @click="addItem">+ Add Product</button>
        </div>

        <!-- Items Table -->
        <div class="card-body">

            <!-- Display items validation error -->
            <div v-if="form.errors.items" class="alert alert-danger">
                {{ form.errors.items }}
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Product</th>
                        <th width="100">Qty</th>
                        <th width="120">Price</th>
                        <th width="120">Total</th>
                        <th width="60">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(item, index) in form.items" :key="index">
                        <td>
                            <select v-model="item.category_id" class="form-control" @change="fetchProducts(item)">
                                <option value="">Select</option>
                                <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                            </select>
                            <!-- Category validation -->
                            <span v-if="form.errors[`items.${index}.category_id`]" class="text-danger">
                                {{ form.errors[`items.${index}.category_id`] }}
                            </span>
                        </td>

                        <td>
                            <select v-model="item.product_id" class="form-control" @change="setPrice(item)">
                                <option value="">Select Product</option>
                                <option v-for="p in item.products" :key="p.id" :value="p.id">{{ p.name }} ({{ p.price }})</option>
                            </select>
                            <!-- Product validation -->
                            <span v-if="form.errors[`items.${index}.product_id`]" class="text-danger">
                                {{ form.errors[`items.${index}.product_id`] }}
                            </span>
                        </td>

                        <td>
                            <input type="number" min="1" v-model="item.quantity" class="form-control" />
                            <!-- Quantity validation -->
                            <span v-if="form.errors[`items.${index}.quantity`]" class="text-danger">
                                {{ form.errors[`items.${index}.quantity`] }}
                            </span>
                        </td>

                        <td>{{ item.price }}</td>
                        <td>{{ item.price * item.quantity }}</td>

                        <td>
                            <button class="btn btn-sm btn-danger" @click="removeItem(index)">✕</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="card-footer">
            <div class="row">
                <div class="col-md-4 offset-md-8">
                    <table class="table">
                        <tr>
                            <th>Sub Total</th>
                            <td>{{ subTotal }}</td>
                        </tr>
                        <tr>
                            <th>Discount</th>
                            <td>
                                <input type="number" min="0" v-model="form.discount" class="form-control">
                                <!-- Discount validation -->
                                <span v-if="form.errors.discount" class="text-danger">{{ form.errors.discount }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Final Amount</th>
                            <td class="fw-bold">{{ finalAmount }}</td>
                        </tr>
                    </table>

                    <button class="btn btn-success w-100" @click="submit">Update Order</button>
                </div>
            </div>
        </div>

    </div>
</AdminLayout>
</template>