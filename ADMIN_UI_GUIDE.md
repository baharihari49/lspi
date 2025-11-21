# Admin UI Implementation Guide

## ✅ What Has Been Completed

### Phase 1: Master Data & User Management (DONE)
- ✅ All migrations created (13 tables total)
- ✅ All models with relationships created
- ✅ Master data seeder with comprehensive data
- ✅ Database successfully migrated and seeded

### Phase 2: Admin Controllers & Routes (COMPLETED)
- ✅ `MasterRoleController` - Full CRUD with permission assignment - COMPLETE
- ✅ `MasterStatusController` - Full CRUD for statuses - COMPLETE
- ✅ `MasterPermissionController` - Full CRUD with role count - COMPLETE
- ✅ `MasterMethodController` - Full CRUD with category support - COMPLETE
- ✅ `MasterDocumentTypeController` - Full CRUD with retention policy - COMPLETE
- ✅ `UserManagementController` - Created (pending implementation)
- ✅ All routes registered in `web.php`

### Phase 3: Admin Views (COMPLETED)
- ✅ Role views (index, create, edit) - COMPLETE
- ✅ Status views (index, create, edit) - COMPLETE
- ✅ Method views (index, create, edit) - COMPLETE
- ✅ Document Type views (index, create, edit) - COMPLETE
- ✅ Permission views (index, create, edit) - COMPLETE
- ✅ Admin sidebar navigation updated with all Master Data links
- ⏳ User management views (pending)

---

## 📋 Next Steps to Complete

### Step 1: Create Remaining Role Views

**File: `resources/views/admin/master-data/roles/create.blade.php`**

```blade
@extends('layouts.admin')

@section('title', 'Create Role')

@php
    $active = 'master-data';
@endphp

@section('page_title', 'Create New Role')
@section('page_description', 'Add a new system role')

@section('content')
    <form action="{{ route('admin.master-roles.store') }}" method="POST" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Basic Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Role Information</h3>

                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Role Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div class="mb-4">
                        <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">Slug (auto-generated if empty)</label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono text-sm @error('slug') border-red-500 @enderror">
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Permissions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assign Permissions</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($permissions as $permission)
                            <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                    class="mt-1 w-4 h-4 text-blue-900 border-gray-300 rounded focus:ring-blue-500"
                                    {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                <div class="ml-3">
                                    <p class="font-semibold text-sm text-gray-900">{{ $permission->name }}</p>
                                    @if($permission->description)
                                        <p class="text-xs text-gray-600 mt-1">{{ $permission->description }}</p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="space-y-3">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">save</span>
                            <span>Save Role</span>
                        </button>
                        <a href="{{ route('admin.master-roles.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">cancel</span>
                            <span>Cancel</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
```

**File: `resources/views/admin/master-data/roles/edit.blade.php`**
- Copy dari create.blade.php
- Change title to "Edit Role"
- Change form action to `route('admin.master-roles.update', $masterRole)`
- Add `@method('PUT')`
- Pre-fill values with `$masterRole->name`, etc
- Check permissions with `in_array($permission->id, $rolePermissions)`

---

### Step 2: Create Status Views

Similar pattern to roles, but simpler (no permissions). Focus on category grouping.

---

### Step 3: Update Admin Sidebar

**File: `resources/views/layouts/admin.blade.php`**

Find the sidebar section and add Master Data menu:

```blade
<!-- Master Data Section -->
<div class="mb-2">
    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Master Data</p>
    <a href="{{ route('admin.master-roles.index') }}" class="flex items-center px-4 py-2 {{ $active === 'master-data' ? 'bg-blue-900 text-white' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition">
        <span class="material-symbols-outlined mr-3">shield</span>
        <span>Roles & Permissions</span>
    </a>
    <a href="{{ route('admin.master-statuses.index') }}" class="flex items-center px-4 py-2 {{ $active === 'master-data' ? 'bg-blue-900 text-white' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition">
        <span class="material-symbols-outlined mr-3">toggle_on</span>
        <span>Statuses</span>
    </a>
    <a href="{{ route('admin.master-methods.index') }}" class="flex items-center px-4 py-2 {{ $active === 'master-data' ? 'bg-blue-900 text-white' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition">
        <span class="material-symbols-outlined mr-3">category</span>
        <span>Methods</span>
    </a>
    <a href="{{ route('admin.master-document-types.index') }}" class="flex items-center px-4 py-2 {{ $active === 'master-data' ? 'bg-blue-900 text-white' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition">
        <span class="material-symbols-outlined mr-3">description</span>
        <span>Document Types</span>
    </a>
</div>

<!-- User Management Section -->
<div class="mb-2">
    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Users</p>
    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 {{ $active === 'users' ? 'bg-blue-900 text-white' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition">
        <span class="material-symbols-outlined mr-3">people</span>
        <span>User Management</span>
    </a>
</div>
```

---

## 🎨 View Pattern for All Master Data

All views should follow this structure:

### Index View Structure:
1. Success/Error messages
2. Header with title + "Add" button
3. Table with:
   - Column headers
   - Data rows with badges/labels
   - Edit/Delete action buttons
4. Pagination links

### Create/Edit View Structure:
1. Form with CSRF token
2. Grid layout (2/3 main + 1/3 sidebar)
3. Main column: Form fields
4. Sidebar: Save/Cancel buttons
5. Validation errors display

---

## 🚀 Quick Implementation Checklist

### For Each Master Data Table:

**1. Controller (if not done):**
```php
// Copy pattern from MasterStatusController.php
- index() with pagination
- create() with related data
- store() with validation
- edit() with related data
- update() with validation
- destroy() with checks
```

**2. Views:**
```
- index.blade.php (list with actions)
- create.blade.php (form)
- edit.blade.php (form with data)
```

**3. Routes:**
```php
Route::resource('master-xxx', MasterXxxController::class);
```

**4. Sidebar Link:**
```blade
<a href="{{ route('admin.master-xxx.index') }}">...</a>
```

---

## 📊 Testing Guide

### Test Each CRUD Operation:

1. **Create:**
   - Go to `/admin/master-roles/create`
   - Fill form
   - Submit
   - Verify redirect to index with success message
   - Verify data in database

2. **Read/List:**
   - Go to `/admin/master-roles`
   - Verify all roles displayed
   - Check pagination works

3. **Edit:**
   - Click edit button
   - Modify data
   - Submit
   - Verify changes saved

4. **Delete:**
   - Click delete button
   - Confirm dialog
   - Verify deleted
   - Test delete protection (role with users)

---

## 🔐 Login Credentials

**Super Admin:**
- Email: `superadmin@lsp-pie.ac.id`
- Password: `password`
- Permissions: ALL (22 permissions)

**Admin:**
- Email: `admin@lsp-pie.ac.id`
- Password: `password`
- Permissions: 16 permissions (no user.delete, roles.manage)

---

## 📝 Database Quick Reference

### Tables Created:
1. `master_roles` (5 records)
2. `master_permissions` (22 records)
3. `master_statuses` (27 records across 7 categories)
4. `master_methods` (12 records across 3 categories)
5. `master_document_types` (12 records)
6. `users` (2 records with roles)
7. `user_profiles`
8. `user_contacts`
9. `files`
10. `role_permission` (pivot)
11. `user_role` (pivot)

### Status Categories:
- `user` (3 statuses)
- `assessment` (4 statuses)
- `apl` (5 statuses)
- `certificate` (3 statuses)
- `payment` (4 statuses)
- `event` (5 statuses)
- `document` (3 statuses)

### Method Categories:
- `assessment` (5 methods)
- `evidence` (3 methods)
- `payment` (4 methods)

---

## 🎯 Priority Order for Completion

1. **HIGH:** Complete Role views (create, edit) - Most complex, needed for access control
2. **HIGH:** Complete Status views - Used everywhere in system
3. **MEDIUM:** Complete User Management - Needed for testing RBAC
4. **MEDIUM:** Document Types & Methods - Less urgent, simpler
5. **LOW:** Permission management - Usually read-only, assigned through roles

---

## 💡 Tips & Best Practices

1. **Always use `@csrf` in forms**
2. **Always validate input in controllers**
3. **Use route model binding** (`MasterRole $masterRole` in controller params)
4. **Display success/error messages** using session flash
5. **Add confirmation dialogs** for delete actions
6. **Group permissions logically** in role form
7. **Show counts** (permissions_count, users_count) for better UX
8. **Add search/filter** for large datasets later

---

## 📚 Resources

- **Laravel Docs:** https://laravel.com/docs/12.x
- **Tailwind CSS:** https://tailwindcss.com/docs
- **Material Icons:** https://fonts.google.com/icons

---

**Last Updated:** 2025-11-21
**Status:** Phase 1, 2, 3 Complete - All Master Data CRUD Fully Implemented

## ✅ **IMPLEMENTATION SUMMARY**

### **What's Been Completed:**

**Controllers (5/5 Complete):**
- ✅ MasterRoleController - Full CRUD with permission assignment
- ✅ MasterStatusController - Full CRUD with category filtering
- ✅ MasterMethodController - Full CRUD with category support
- ✅ MasterDocumentTypeController - Full CRUD with retention management
- ✅ MasterPermissionController - Full CRUD with role tracking

**Views (15/15 Complete):**
- ✅ Roles: index, create, edit
- ✅ Statuses: index, create, edit
- ✅ Methods: index, create, edit
- ✅ Document Types: index, create, edit
- ✅ Permissions: index, create, edit

**Navigation:**
- ✅ Admin sidebar fully updated with all Master Data links
- ✅ Proper active state highlighting
- ✅ Material Icons integration

### **Access URLs:**
- `/admin/master-roles` - Role management
- `/admin/master-statuses` - Status management
- `/admin/master-methods` - Method management
- `/admin/master-document-types` - Document type management
- `/admin/master-permissions` - Permission management
