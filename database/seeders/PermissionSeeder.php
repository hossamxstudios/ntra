<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        try {
            DB::beginTransaction();

            // Reset cached roles and permissions
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // Define permissions by module with Arabic translations
            $permissions = [

                // User Management
                'users.view' => 'عرض المستخدمين',
                'users.create' => 'إضافة مستخدم',
                'users.edit' => 'تعديل مستخدم',
                'users.delete' => 'حذف مستخدم',
                'users.restore' => 'استعادة مستخدم',
                'users.force-delete' => 'حذف نهائي للمستخدم',
                'users.bulk-delete' => 'حذف مجموعة مستخدمين',
                'users.bulk-restore' => 'استعادة مجموعة مستخدمين',
                'users.bulk-force-delete' => 'حذف نهائي لمجموعة مستخدمين',
                'users.toggle-status' => 'تغيير حالة المستخدم',
                'users.assign-role' => 'تعيين صلاحية للمستخدم',
                'users.change-password' => 'تغيير كلمة مرور المستخدم',

                // Roles & Permissions
                'roles.view' => 'عرض الأدوار',
                'roles.create' => 'إضافة دور',
                'roles.edit' => 'تعديل دور',
                'roles.delete' => 'حذف دور',
                'roles.bulk-delete' => 'حذف مجموعة أدوار',
                'roles.sync-permissions' => 'مزامنة الأذونات',

                // Reports
                'reports.view' => 'عرض التقارير',
                'reports.export' => 'تصدير التقارير',
                'reports.create' => 'إنشاء تقرير',

                // Activity Logs
                'activity-logs.view' => 'عرض سجل النشاطات',
                'activity-logs.delete' => 'حذف سجل النشاطات',
                'activity-logs.export' => 'تصدير سجل النشاطات',

                // Backup
                'backup.access' => 'الوصول للنسخ الاحتياطي',
                'backup.create' => 'إنشاء نسخة احتياطية',
                'backup.delete' => 'حذف نسخة احتياطية',

                // ==================== NTRA System Permissions ====================

                // Machines Management
                'machines.view' => 'عرض أجهزة الخدمة',
                'machines.create' => 'إضافة جهاز خدمة',
                'machines.edit' => 'تعديل جهاز خدمة',
                'machines.delete' => 'حذف جهاز خدمة',

                // Mobile Devices Management
                'mobile-devices.view' => 'عرض الأجهزة المحمولة',
                'mobile-devices.create' => 'إضافة جهاز محمول',
                'mobile-devices.edit' => 'تعديل جهاز محمول',
                'mobile-devices.delete' => 'حذف جهاز محمول',
                'mobile-devices.activate' => 'تفعيل جهاز محمول',
                'mobile-devices.lock' => 'حظر جهاز محمول',
                'mobile-devices.export' => 'تصدير الأجهزة المحمولة',

                // Passengers Management
                'passengers.view' => 'عرض المسافرين',
                'passengers.create' => 'إضافة مسافر',
                'passengers.edit' => 'تعديل بيانات مسافر',
                'passengers.delete' => 'حذف مسافر',
                'passengers.export' => 'تصدير بيانات المسافرين',

                // IMEI Checks Management
                'imei-checks.view' => 'عرض فحوصات IMEI',
                'imei-checks.create' => 'إضافة فحص IMEI',
                'imei-checks.edit' => 'تعديل فحص IMEI',
                'imei-checks.delete' => 'حذف فحص IMEI',
                'imei-checks.scan' => 'فحص IMEI',
                'imei-checks.export' => 'تصدير فحوصات IMEI',

                // Payments Management
                'payments.view' => 'عرض المدفوعات',
                'payments.create' => 'إضافة دفعة',
                'payments.edit' => 'تعديل دفعة',
                'payments.delete' => 'حذف دفعة',
                'payments.refund' => 'استرداد دفعة',
                'payments.export' => 'تصدير المدفوعات',
                'payments.receipt' => 'طباعة إيصال',

                // Suggestions Management
                'suggestions.view' => 'عرض الاقتراحات',
                'suggestions.create' => 'إضافة اقتراح',
                'suggestions.edit' => 'تعديل اقتراح',
                'suggestions.delete' => 'حذف اقتراح',
                'suggestions.export' => 'تصدير الاقتراحات',

                // Complaints Management
                'complaints.view' => 'عرض الشكاوى',
                'complaints.create' => 'إضافة شكوى',
                'complaints.edit' => 'تعديل شكوى',
                'complaints.delete' => 'حذف شكوى',
                'complaints.resolve' => 'حل شكوى',
                'complaints.export' => 'تصدير الشكاوى',
            ];

            // Create permissions
            foreach ($permissions as $name => $description) {
                Permission::firstOrCreate(
                    ['name' => $name, 'guard_name' => 'web']
                );
            }

            // Define roles with their permissions
            $roles = [
                'Super Admin' => array_keys($permissions), // All permissions

                'NTRA Manager' => [
                    // Full access to NTRA modules
                    'machines.view', 'machines.create', 'machines.edit', 'machines.delete',
                    'mobile-devices.view', 'mobile-devices.create', 'mobile-devices.edit', 'mobile-devices.delete',
                    'mobile-devices.activate', 'mobile-devices.lock', 'mobile-devices.export',
                    'passengers.view', 'passengers.create', 'passengers.edit', 'passengers.delete', 'passengers.export',
                    'imei-checks.view', 'imei-checks.create', 'imei-checks.edit', 'imei-checks.delete',
                    'imei-checks.scan', 'imei-checks.export',
                    'payments.view', 'payments.create', 'payments.edit', 'payments.delete',
                    'payments.refund', 'payments.export', 'payments.receipt',
                    'suggestions.view', 'suggestions.create', 'suggestions.edit', 'suggestions.delete', 'suggestions.export',
                    'complaints.view', 'complaints.create', 'complaints.edit', 'complaints.delete',
                    'complaints.resolve', 'complaints.export',
                    'activity-logs.view',
                ],

                'NTRA Operator' => [
                    // Device registration workflow
                    'machines.view',
                    'mobile-devices.view', 'mobile-devices.create', 'mobile-devices.edit',
                    'passengers.view', 'passengers.create', 'passengers.edit',
                    'imei-checks.view', 'imei-checks.create', 'imei-checks.scan',
                    'payments.view', 'payments.create', 'payments.receipt',
                    'suggestions.view', 'suggestions.create',
                    'complaints.view', 'complaints.create',
                ],

                'Finance Officer' => [
                    // Payment and reporting access
                    'payments.view', 'payments.export', 'payments.receipt', 'payments.refund',
                    'mobile-devices.view', 'mobile-devices.export',
                    'passengers.view', 'passengers.export',
                    'imei-checks.view', 'imei-checks.export',
                ],

                'Support Staff' => [
                    // Complaints and suggestions handling
                    'complaints.view', 'complaints.edit', 'complaints.resolve', 'complaints.export',
                    'suggestions.view', 'suggestions.edit', 'suggestions.export',
                    'passengers.view',
                    'mobile-devices.view',
                ],

                'Viewer' => [
                    'machines.view',
                    'mobile-devices.view',
                    'passengers.view',
                    'imei-checks.view',
                    'payments.view',
                ],
            ];

            // Create roles and assign permissions
            foreach ($roles as $roleName => $rolePermissions) {
                $role = Role::firstOrCreate(
                    ['name' => $roleName, 'guard_name' => 'web']
                );
                $role->syncPermissions($rolePermissions);
            }

            DB::commit();

            $this->command->info('Permissions and roles seeded successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PermissionSeeder failed: ' . $e->getMessage());
            $this->command->error('Failed to seed permissions: ' . $e->getMessage());
        }
    }
}
