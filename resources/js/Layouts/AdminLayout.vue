<script setup>
import { Link, Head, usePage } from '@inertiajs/vue3'
const page = usePage()
const user = page.props.auth?.user
const permissions = page.props.auth.permissions || []
const can = (permission) => permissions.includes(permission)
</script>

<template>
  <Head title="Admin Panel" />

  <div class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper">

      <!-- Header -->
      <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">

          <!-- Left -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block">
              <Link href="/dashboard" class="nav-link">Home</Link>
            </li>
          </ul>

          <!-- Right -->
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <Link href="/profile" class="nav-link">
                <i class="bi bi-person-circle"></i>
              </Link>
            </li>
          </ul>

        </div>
      </nav>

      <!-- Sidebar -->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
          <Link href="/dashboard" class="brand-link">
            <span class="brand-text fw-light">AdminLTE</span>
          </Link>
        </div>
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">
              <li class="nav-item">
                <Link href="/dashboard" class="nav-link">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>Dashboard</p>
                </Link>
              </li>
              <li v-if="can('view users')" class="nav-item">
                <Link :href="route('users.index')" class="nav-link">
                    <i class="nav-icon bi bi-person"></i>
                    <p>Users</p>
                </Link>
            </li>
              <li v-if="can('view categories')" class="nav-item">
                <Link href="/categories" class="nav-link">
                  <i class="nav-icon bi bi-list"></i>
                  <p>Categories</p>
                </Link>
              </li>

              <li v-if="can('view products')" class="nav-item">
                <Link href="/products" class="nav-link">
                  <i class="nav-icon bi bi-box"></i>
                  <p>Products</p>
                </Link>
              </li>

              <li v-if="can('view orders')" class="nav-item">
                <Link href="/orders" class="nav-link">
                  <i class="nav-icon bi bi-box"></i>
                  <p>Orders</p>
                </Link>
              </li>

              <li class="nav-item">
                <Link
                  href="/logout"
                  method="post"
                  as="button"
                  class="nav-link btn btn-link text-start"
                >
                  <i class="nav-icon bi bi-box-arrow-right"></i>
                  <p>Logout</p>
                </Link>
              </li>
            </ul>
          </nav>
        </div>
      </aside>

      <!-- Main Content -->
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <slot />
          </div>
        </div>
      </main>

    </div>
  </div>
</template>
