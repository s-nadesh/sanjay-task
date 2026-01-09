<template>
    <AdminLayout title="Create Category">
        <div class="card">
            <div class="card-body">
                <form @submit.prevent="submit">

                    <!-- Name -->
                    <div class="mb-3">
                        <label>Name <span class="text-danger">*</span></label>
                        <input v-model="form.name" class="form-control" />
                        <div class="text-danger">{{ form.errors.name }}</div>
                    </div>

                    <!-- Parent Category Dropdown -->
                    <div class="mb-3">
                        <label>Parent Category</label>
                        <select v-model="form.parent_id" class="form-select">
                            <option value="">None</option>
                            <option 
                                v-for="option in categoryOptions" 
                                :key="option.id" 
                                :value="option.id">
                                {{ option.indentedName }}
                            </option>
                        </select>
                        <div class="text-danger">{{ form.errors.parent_id }}</div>
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea v-model="form.description" class="form-control"></textarea>
                        <div class="text-danger">{{ form.errors.description }}</div>
                    </div>

                    <div class="mb-3">
                        <label>Category Image</label>
                        <input type="file" class="form-control"
                            @change="e => form.image = e.target.files[0]" />
                        <div class="text-danger">{{ form.errors.image }}</div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label>Status</label>
                        <select v-model="form.status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <button class="btn btn-success">Save</button>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    categories: Array
})

const categories = props.categories

// Form
const form = useForm({
    name: '',
    parent_id: '',
    description: '',
    image: null,
    status: 1,
})

// Submit
const submit = () => {
    form.post(route('categories.store'), {
        forceFormData: true
    })
}
const categoryOptions = flattenCategories(categories)
// Recursive flattening for dropdown
function flattenCategories(categories, level = 0) {
    let result = []

    categories.forEach(cat => {
        result.push({
            id: cat.id,
            indentedName: `${'— '.repeat(level)}${cat.name}`
        })

        if (cat.children_recursive?.length) {
            result = result.concat(
                flattenCategories(cat.children_recursive, level + 1)
            )
        }
    })

    return result
}


</script>
